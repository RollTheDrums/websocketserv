<?php
require_once 'bin/PHP-Websockets-master/ws-cli.php';

$pdo = new PDO('mysql:host=localhost;dbname=socket_db', 'root', '');

$stmt = $pdo->prepare('INSERT INTO test (from_id, message_text, message_time) 
VALUES(\''.$_POST['from_id'].'\', \''.$_POST['message_text'].'\', \''.$_POST['message_time'].'\');');
$stmt->execute();

$ws = new ws(array(
    'host' => 'localhost',
    'port' => '1111',
    'path' => ''
));

$result = $ws->send('');
$ws->close();