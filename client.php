<?php

$mysqli = mysqli_connect('localhost', 'root', '', 'gearman');
# Создание клиентского объекта
$gmclient= new GearmanClient();

# Указание сервера по умолчанию (localhost).
$gmclient->addServer('localhost', 4730);

$gmclient->setCompleteCallback('taskCompleted');

function taskCompleted($task) {
    echo "Task # " . $task->jobHandle() . " has been completed <br />";
}

$query = 'SELECT * FROM orders';
$result = mysqli_query($mysqli, $query);
$i = 6;
echo "Sending ".$i." jobs...<br />";

$start_profiler = microtime(true);
while ($i) {
    $row = array($i, $i, 0);
    $row2 = array(13131, 24242);
    $result = $gmclient->doBackground("ordersSimul", serialize($row));
    //$result = $gmclient->doBackground("ordersSimul", serialize($row2));
    //$result = $gmclient->addTask("ordersSimul", serialize($row));
    //$gmclient->runTasks();


    $i--;
    $done = false;
    do {
        usleep(500000);
        $stat = $gmclient->jobStatus($result);
        if (!$stat[0]) {
            $done = true;
        }
        echo "Is Working?: " . ($stat[1] ? "true" : "false") . ", mails sent : " . $stat[2] . ", total: " . $stat[3] . "<br />";
    } while (!$done);

    flush();
}
$stop_profiler = (round((microtime(true) - $start_profiler), 3) * 1);
echo $stop_profiler . ' seconds elapsed for sending all tasks.<br /><br />';

class GearmanClient
{
    //public function

}



function getOrder() {
	//

	//$orders = mysqli_fetch_assoc($result);
	$order = generateRandomString(10);
	return $order;
}

function generateRandomString($length = 10) {
	//return $random = substr(md5(rand()),0,7);
	return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}