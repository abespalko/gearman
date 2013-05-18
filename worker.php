<?php
header("Content-Type: text/html");
echo "Starting..\n";

# Создание нового обработчика.
$gmworker = new GearmanWorker();

# Добавление сервера по умолчанию (localhost).
$gmworker->addServer('localhost', 4730);

# Регистрация функции "reverse" на сервере. Изменение функции обработчика на
# "reverse_fn_fast" для более быстрой обработки без вывода.
$gmworker->addFunction("ordersSimul", "ordersSimul");

print "Waiting for job...\n";
while($gmworker->work())
{

	if ($gmworker->returnCode() != GEARMAN_SUCCESS)
	{
		print "return_code: " . $gmworker->returnCode() . "\n";
		break;
	}

}

function ordersSimul($job)
{

    print "New task found: " . __FUNCTION__ . ".\nStarting work...\n\n";
    $orders = $job->workload();
    $orders = unserialize($orders);
    $totalMails = 50;

    // Do manipulation with order
    /*for ($sentMail=0; $sentMail<=$totalMails; $sentMail++) {
        usleep(100000);
        $job->sendStatus($sentMail, $totalMails);
    }*/

    return 'Orders convertered:' . $orders;
}

function getOrders() {
	//$mysqli = mysqli_connect('localhost', 'root', '', 'gearman');
	//$query = 'SELECT * FROM orders';
	//$result = mysqli_query($mysqli, $query);
	//$orders = mysqli_fetch_assoc($result);
	$orders = array_fill(0, 5, generateRandomString(10));

	return $orders;
}


function generateRandomString($length = 10) {
	//return $random = substr(md5(rand()),0,7);
	return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}