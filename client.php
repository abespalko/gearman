<?php

# Создание клиентского объекта
$gmclient= new GearmanClient();

# Указание сервера по умолчанию (localhost).
$gmclient->addServer();

echo "Sending job\n";

# Отправка задания обратно
do
{
  $result = $gmclient->do("reverse", "Hello!");

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
