## SQL for the Task class
CREATE DATABASE task_scheduler;

USE task_scheduler;

CREATE TABLE `tasks` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `scheduled_time` datetime NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `command` text NOT NULL,
  `status` enum('pending','postponed','before','completed','failed') DEFAULT 'pending',
  `log` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;