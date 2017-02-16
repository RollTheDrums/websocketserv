<?php
require_once('simple-serv.php');


class DaemonClass {

    public $maxProcesses = 5;
    protected $stopServer = FALSE;
    protected $currentJobs = array();


    public function __construct() {
        echo "Constructed daemon controller".PHP_EOL;

        pcntl_signal(SIGTERM, array($this, "childSignalHandler"));
        pcntl_signal(SIGCHLD, array($this, "childSignalHandler"));
    }


    public function run() {
        echo "Running daemon controller".PHP_EOL;

        $host = 'localhost';
        $port = 1111;
        $server = new echo_server($host , $port);
        array_push($this->currentJobs, $server->run());
        while (!$this->stopServer) {
            while(count($this->currentJobs) >= $this->maxProcesses) {
                echo "Maximum children allowed, waiting...".PHP_EOL;
                sleep(1);
            }

            $this->launchJob();
        }
    }


    protected function launchJob() {
        $pid = pcntl_fork();

        if($pid == -1) {
            error_log('Could not launch new job, exiting.');
            return FALSE;
        } elseif($pid)
            $this->currentJobs[$pid] = TRUE;
        else {
            echo "Process ID ".getmypid().PHP_EOL;
            exit();
        }

        return TRUE;
    }


    public function childSignalHandler($signo, $pid = null, $status = null) {
        switch($signo) {
            case SIGTERM:
                // При получении сигнала завершения работы устанавливаем флаг
                $this->stop_server = true;
                break;
            case SIGCHLD:
                // При получении сигнала от дочернего процесса
                if (!$pid) {
                    $pid = pcntl_waitpid(-1, $status, WNOHANG);
                }
                // Пока есть завершенные дочерние процессы
                while ($pid > 0) {
                    if ($pid && isset($this->currentJobs[$pid])) {
                        // Удаляем дочерние процессы из списка
                        unset($this->currentJobs[$pid]);
                    }
                    $pid = pcntl_waitpid(-1, $status, WNOHANG);
                }
                break;
            default:
                // все остальные сигналы
        }
    }
}