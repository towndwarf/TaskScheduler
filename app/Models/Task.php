<?php

namespace App\Models;

/* SQL for the Task class
CREATE DATABASE task_scheduler;

USE task_scheduler;

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `scheduled_time` datetime NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `command` text NOT NULL,
  `status` enum('pending','postponed','before','completed','failed') DEFAULT 'pending',
  `log` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

 */

class Task
{
    public string $time;
    public string $command;

    public function __construct(string $time, string $command)
    {
        $this->time = $time;
        $this->command = $command;
    }

    public function run(): string|false {
        $last_str = null;
        if (system(escapeshellcmd($this->command),$last_str)) {
                return $last_str;
        }
        return false;
    }
}