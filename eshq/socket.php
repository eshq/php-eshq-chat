<?php

require_once '../include/eshq.php';

$channel = $_POST['channel'];

if (!$channel) { throw('No channel specified'); }

$eshq = new ESHQ();
$socket = $eshq->open(array('channel' => $channel));

echo json_encode(array('socket' => $socket));

?>
