<?php

namespace App\Services;

use App\Models\Task;
use DateTime;
use Exception;
use PDO;
use UnexpectedValueException;



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

    public function addTask(Task $new_task): void
    {
        $query = "INSERT INTO tasks (scheduled_time, command, created_at) 
                  VALUES (:scheduled_time, :command , NOW())";
        $statement = $this->db->prepare($query);
        $statement->execute([
            ':scheduled_time' => $new_task->time,
            ':command' => '"' . $new_task->command . '"',
        ]);
    }

    public function getPendingTasks(): array
    {
        $query = "SELECT * FROM tasks WHERE status = 'pending' OR status = 'before' ORDER BY scheduled_time";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param int $taskId
     * @param string $status
     * @param string $message
     * @return void
     * @throws UnexpectedValueException
     */
    public function changeTaskStatus(int $taskId, string $status, string $message): void
    {
        if (!in_array($status, ['pending','postponed', 'before', 'completed', 'failed'],true)) {
            throw new UnexpectedValueException('Improper task status `'.$status . '`');
        }

        $query = "UPDATE tasks SET status = :status, log = :log WHERE id = :id";
        $statement = $this->db->prepare($query);
        $statement->execute([':status' => $status, ':log' => '"' . $message . '"', ':id' => $taskId]);
    }


    /**
     * @throws Exception
     */
    public function runDueTasks(): void
    {
        $tasks = $this->getPendingTasks();
        echo 'tasks: ' . count($tasks);
        $time_now = new DateTime();

        foreach ($tasks as $scheduled_task) {
            if (new DateTime($scheduled_task['scheduled_time']) <= $time_now) {
                echo 'Executing: ' . $scheduled_task['command'] . PHP_EOL;

                $this->changeTaskStatus($scheduled_task['id'], 'before', 'before execution');

                try {
                    $task = new Task($scheduled_task['scheduled_time'], $scheduled_task['command']);
                    $ret_val = $task->run();
                    if ($ret_val >= 0) {
                        $this->changeTaskStatus($scheduled_task['id'], 'completed', $ret_val);
                    } else {
                        $this->changeTaskStatus($scheduled_task['id'], 'failed', $ret_val);
                    }
                } catch(Exception $exception) {
                    $this->changeTaskStatus($scheduled_task['id'], 'failed', 'exception: ' . $exception->getMessage());
                }
            }

        }
    }
}