<?php

require_once('PHP-Websockets-master/websockets.php');

class echo_server extends WebSocketServer
{
    //protected $maxBufferSize = 1048576; //1MB... overkill for an echo server, but potentially plausible for other applications.

    protected function process ($user, $message) {
        foreach ($this->users as $u) {
            $this->send($u, $message);
        }
        echo "Requested resource : " . $message . "\n";
    }

    protected function connected ($user) {
        $welcome_message = "Hello {$user->id}. Welcome to the Websocket server. Type help to see what commands are available.";
        $this->send($user, $welcome_message);
    }

    protected function closed ($user) {
        echo "User closed connectionn";
    }
}


class DaemonClass {

    public $maxProcesses = 5;
    protected $stopServer = FALSE;
    protected $currentJobs = array();


    public function __construct() {
        echo "Constructed daemon controller".PHP_EOL;

//        pcntl_signal(SIGTERM, array($this, "childSignalHandler"));
//        pcntl_signal(SIGCHLD, array($this, "childSignalHandler"));
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
                $this->stop_server = true;
                break;
            case SIGCHLD:
                if (!$pid) {
                    $pid = pcntl_waitpid(-1, $status, WNOHANG);
                }
                while ($pid > 0) {
                    if ($pid && isset($this->currentJobs[$pid])) {
                        unset($this->currentJobs[$pid]);
                    }
                    $pid = pcntl_waitpid(-1, $status, WNOHANG);
                }
                break;
            default:
        }
    }
}


$daemon = new DaemonClass();
$daemon->run();