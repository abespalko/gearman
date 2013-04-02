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
	print "New task found.. Starting work...\n";
	$start_profiler = microtime(true);
	if ($gmworker->returnCode() != GEARMAN_SUCCESS)
	{
		print "return_code: " . $gmworker->returnCode() . "\n";
		break;
	}
	$stop_profiler = round((microtime(true) - $start_profiler));
	echo $stop_profiler . ' seconds elapsed for tast: ';
   // die();
}

function reverse_fn($job)
{
  echo "Received job: " . $job->handle() . "\n";

  $workload = $job->workload();
  $workload_size = $job->workloadSize();

  echo "Workload: $workload ($workload_size)\n";

  # Этот цикл не является необходимым, но показывает как выполняется работа
  for ($x= 0; $x < $workload_size; $x++)
  {
    echo "Sending status: " . ($x + 1) . "/$workload_size complete\n";
    $job->sendStatus($x, $workload_size);
    sleep(1);
  }

  $result= strrev($workload);
  echo "Result: $result\n";

  # Возвращаем, когда необходимо отправить результат обратно клиенту.
  return $result;
}

# Гораздо более простая и менее подробная версия вышеприведенной функции выглядит так:
function reverse_fn_fast($job)
{
  return strrev($job->workload());
}

function ordersSimul() {

	$orders = getOrders();
	foreach ($orders as $order) {
		// Do manipulation with order
		sleep(1);
	}
    return 'Order convertered.';
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