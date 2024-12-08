<?php

namespace App\Commands;
use App\Models\Task;
use App\Services\Scheduler;

class AddCommand
{
    public function handle(array $arguments): array
    {
        if (count($arguments) < 3) {
            return ['code' => -1,
                    'msg'=> 'Usage: task:add <time_offset> <command>'];
        }
        $time_offset = $arguments[1];
        $command = $arguments[2];

        $timestamp = strtotime($time_offset);
        if (!$timestamp) {
            return ['code' => -1,
                    'msg' => 'TASK NOT ADDED: unable to evaluate the execution time, the command  ($command) was not added'];
        }

        $call_time = date('Y-m-d H:i:s', $timestamp);
        if ($call_time > date('Y-m-d H:i:s')) {
            return ['code' => -1,
                    'msg' => 'TASK NOT ADDED: the given execution time is less than the current timestamp, the command  ($command) was not added'];
        }
        $new_task = new Task($call_time, $command);
        $scheduler = new Scheduler();
        $scheduler->addTask($new_task);

        return ['code' => 1,
                'msg' => "Task added: [To run at: $call_time, Command: $command]"];
    }
}