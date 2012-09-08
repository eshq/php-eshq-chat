<?php

require_once '../includes/eshq.php';

$msg  = $_POST['msg'];
$nick = $_POST['nick'];

if (!$msg) { throw('No message'); }

$eshq = new ESHQ();
$eshq->send(array(
  'channel' => 'chat',
  'data' => json_encode(array(
    'nick' => $nick,
    'msg'  => $msg
  ))
);

?>
