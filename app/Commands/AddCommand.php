<?php

namespace App\Commands;
use App\Models\Task;
use App\Services\Scheduler;

class AddCommand
{
    public function handle(array $arguments): void
    {
        if (count($arguments) < 3) {
            echo 'Usage: task:add <time_offset> <command>' . PHP_EOL;
            return;
        }

        $timeOffset = $arguments[1];
        $command = $arguments[2];
        $timestamp = strtotime($timeOffset);
        if (!$timestamp) {
            echo 'TASK NOT ADDED: unable to evaluate the execution time, the command  ($command) was not added';
            return;
        }
        $time = date('Y-m-d H:i:s', $timestamp);
        if ($time > date('Y-m-d H:i:s')) {
            echo 'TASK NOT ADDED: the given execution time is less than the current timestamp, the command  ($command) was not added';
            return;
        }
        $task = new Task($time, $command);
        $scheduler = new Scheduler();
        $scheduler->addTask($task);

        echo "Task added: [To run at: $time, Command: $command]" . PHP_EOL;
    }
}