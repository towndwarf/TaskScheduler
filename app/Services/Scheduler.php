<?php

namespace App\Services;

use App\Models\Task;

class Scheduler
{
    public function addTask(Task $task): void
    {

    }

    public function getTasks(): array
    {
        return [];
    }

    public function runDueTasks(): void
    {
        $tasks = $this->getTasks();
        $now = time();
        $remainingTasks = [];

        foreach ($tasks as $task) {
            if (strtotime($task->time) <= $now) {
                echo "Executing: {$task->command}" . PHP_EOL;

                // here goes task execution logic


            } else {
                $remainingTasks[] = $task->toArray();
            }
        }


    }
}