<?php

require_once __DIR__ . '/../vendor/autoload.php';
use App\Services\CommandParser;
use App\Commands\AddCommand;
use App\Services\Scheduler;
use App\Models\Task;
use App\Services\AddTaskTrait;

$arguments = $argv;
$command = $arguments[1] ?? null;

switch ($command) {

    /*case 'task:add':
        array_shift($arguments);
        try {
            $ret_val = ((new AddCommand())->handle($arguments));
            echo '[' . ($ret_val['code'] < 0 ?'FAILED':'SCHEDULED') . ']: ' . $ret_val['msg'];
        } catch (Exception $exception) {
            echo '[FAILED]: ' . $exception->getMessage();
        }
        break;
*/
    case 'task:add':
        Scheduler::addTaskArguments($arguments);
        break;


    case 'task:run':
        try {
            (new Scheduler())->runDueTasks();
            echo '.';
        } catch (Exception $exception) {
            echo 'FAILED: ' . $exception->getTraceAsString();
        }
        break;

    default:
        echo 'Available commands:' . PHP_EOL;
        echo '  task:add "<human-readable command>" - Add a new task.' . PHP_EOL;
        echo '  task:add <time_offset> <command> - Add a task to the scheduler. Task must be a valid executable command.' . PHP_EOL;
        echo '  task:run - Execute due tasks.' . PHP_EOL;
}

