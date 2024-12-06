<?php

namespace App\Services;

use App\Models\Task;
use PDO;

/* SQL for the Task class
CREATE DATABASE task_scheduler;

USE task_scheduler;

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    scheduled_time DATETIME NOT NULL,
    command TEXT NOT NULL,
    status ENUM('pending','postponed', 'before', 'completed', 'failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    log  TEXT NULL
);

 */

class Scheduler
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // for possible future changes time check is placed into a different function
    private function checkUserTime(string $usertime): int|false {
        return strtotime($usertime);
    }

    public function addTask(Task $task): void
    {



        $query = "INSERT INTO tasks (scheduled_time, command) 
                  VALUES (:scheduled_time, :command )";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':scheduled_time' => $task->time,
            ':command' => $task->command,
        ]);
    }

    public function getPendingTasks(): array
    {
        $query = "SELECT * FROM tasks WHERE status = 'pending' ORDER BY scheduled_time";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function changeTaskStatus(int $taskId, string $status, string $message): void
    {
        if (!in_array($status, ['pending', 'postponed', 'completed', 'failed'],true)) {
            throw new \UnexpectedValueException('Improper task status `'.$status . '`');
        }

        $query = "UPDATE tasks SET status = CONCAT_WS(';', status, 
                CONCAT_WS(' ', date_format(CURRENT_TIMESTAMP,'%Y%m%d_%H%i%s'), ':status'))
               , log = ':log' WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':status' => $status, ':log' => $message, ':id' => $taskId]);
    }


    /**
     * @throws \Exception
     */
    public function runDueTasks(): void
    {
        $tasks = $this->getPendingTasks();
        $now = new \DateTime();
        $remainingTasks = [];

        foreach ($tasks as $task) {
            if (strtotime($task->time) <= $now) {
                echo "Executing: {$task->command}" . PHP_EOL;

                // here goes task execution logic


            } else {
                $remainingTasks[] = $task->toArray();
            }
        }

        $tasks = $this->getPendingTasks();
        $now = new \DateTime();

        foreach ($tasks as $task) {
            if (new \DateTime($task['scheduled_time']) <= $now) {
                echo "Executing: {$task['command']}" . PHP_EOL;

                $this->changeTaskStatus($task['id'], 'before', 'before execution');

                try {
                    $ret = $task->run();
                    if ($ret) {
                        $this->changeTaskStatus($task['id'], 'completed', $ret);
                    } else {
                        $this->changeTaskStatus($task['id'], 'failed', $ret);
                    }
                } catch(\Exception $exception) {
                    $this->changeTaskStatus($task['id'], 'failed', 'exception: ' . $exception->getMessage());
                }
            }
        }
    }
}