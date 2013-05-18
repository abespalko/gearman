<?php
header('Content-Type: text/html; charset=utf-8');
include_once "client_form.php";

# Создание клиентского объекта
$gmclient = new GearmanClient();

# Указание сервера по умолчанию (localhost).
//$gmclient->addServer('localhost', 4730);

//$gmclient->setCompleteCallback('taskCompleted');

$count_jobs = 0;
if (isset($_GET['count_jobs']) && is_numeric($_GET['count_jobs']) && $_GET['count_jobs'] != '') {
	$count_jobs = $_GET['count_jobs'];
	echo '<br />Sending ' . $count_jobs . ' jobs...<br />';
}



$start_profiler = microtime(true);
for ($i=0; $i>=$count_jobs; $i++) {
    $row = array($count_jobs);
    //$result = $gmclient->doBackground("ordersSimul", serialize($row));
    //$result = $gmclient->doBackground("ordersSimul", serialize($row2));
    //$result = $gmclient->addTask("ordersSimul", serialize($row));
    //$gmclient->runTasks();

/*
    $done = false;
    do {
        usleep(500000);
        $stat = $gmclient->jobStatus($result);
        if (!$stat[0]) {
            $done = true;
        }
        echo "Is Working?: " . ($stat[1] ? "true" : "false") . ", mails sent : " . $stat[2] . ", total: " . $stat[3] . "<br />";
    } while (!$done);
*/
    flush();
}
$stop_profiler = (round((microtime(true) - $start_profiler), 3) * 1);
echo $stop_profiler . ' seconds elapsed for sending ' . $count_jobs . ' tasks.<br /><br />';

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

function taskCompleted($task) {
	echo "Task # " . $task->jobHandle() . " has been completed <br />";
}