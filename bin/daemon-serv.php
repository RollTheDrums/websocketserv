<?php

$child_pid = pcntl_fork();
if($child_pid) {
    exit();
}

posix_setsid();

$baseDir = dirname(__FILE__);
ini_set('error_log', $baseDir.'/error.log');
fclose(STDIN);
fclose(STDOUT);
fclose(STDERR);
$STDIN = fopen('/dev/null', 'r');
$STDOUT = fopen($baseDir.'/application.log', 'ab');
$STDERR = fopen($baseDir.'/daemon.log', 'ab');

include 'DaemonClass.php';
$daemon = new DaemonClass();
$daemon->run();
