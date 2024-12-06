<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Commands\AddCommand;
use App\Services\Scheduler;

$arguments = $argv;
$command = $arguments[1] ?? '';

switch ($command) {
    case 'task:add':
        array_shift($arguments);
        (new AddCommand())->handle($arguments);
        break;

    case 'task:run':
        (new Scheduler())->runDueTasks();
        break;

    default:
        echo 'Available commands:' . PHP_EOL;
        echo '  task:add <time_offset> <command> - Add a task to the scheduler.' . PHP_EOL;
        echo '  task:run - Execute due tasks.' . PHP_EOL;
}

