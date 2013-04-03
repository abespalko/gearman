<?php

# Создание клиентского объекта
$gmclient= new GearmanClient();

# Указание сервера по умолчанию (localhost).
$gmclient->addServer('localhost', 4730);

echo "Sending job\n";

# Отправка задания обратно
do
{
  $result = $gmclient->doBackground("ordersSimul", "Hello!");

  # Проверка на различные возвращаемые пакеты и ошибки.
  switch($gmclient->returnCode())
  {
    case GEARMAN_WORK_DATA:
      echo "Data: $result\n";
      break;
    case GEARMAN_WORK_STATUS:
      list($numerator, $denominator)= $gmclient->doStatus();
      echo "Status: $numerator/$denominator complete\n";
      break;
    case GEARMAN_WORK_FAIL:
      echo "Failed\n";
      exit;
    case GEARMAN_SUCCESS:
      break;
    default:
      echo "RET: " . $gmclient->returnCode() . "\n";
      exit;
  }
}
while($gmclient->returnCode() != GEARMAN_SUCCESS);



function getOrder() {
	//$mysqli = mysqli_connect('localhost', 'root', '', 'gearman');
	//$query = 'SELECT * FROM orders';
	//$result = mysqli_query($mysqli, $query);
	//$orders = mysqli_fetch_assoc($result);
	$order = generateRandomString(10);
	return $order;
}

function generateRandomString($length = 10) {
	//return $random = substr(md5(rand()),0,7);
	return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}