<?php

echo "Starting\n";

# Создание нового обработчика.
$gmworker= new GearmanWorker();

# Добавление сервера по умолчанию (localhost).
$gmworker->addServer('localhost', 4730);

# Регистрация функции "reverse" на сервере. Изменение функции обработчика на
# "reverse_fn_fast" для более быстрой обработки без вывода.
$gmworker->addFunction("reverse", "reverse_fn");

print "Waiting for job...\n";
while($gmworker->work())
{
  if ($gmworker->returnCode() != GEARMAN_SUCCESS)
  {
    echo "return_code: " . $gmworker->returnCode() . "\n";
    break;
  }
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