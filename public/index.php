<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Models\Task;
use App\Commands\AddCommand;
use App\Services\Scheduler;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $time_offset = $_POST['time_offset'];
    $command = $_POST['command'];
    Scheduler::addTaskArguments([$time_offset, 'default', $command]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Task Scheduler</title>
</head>
<body>
<form method="POST">
    <label for="time_offset">Time Offset:</label>
    <input type="text" id="time_offset" name="time_offset" required>
    <br>
    <label for="command">Command:</label>
    <input type="text" id="command" name="command" required>
    <br>
    <button type="submit">Schedule Task</button>
</form>
</body>
</html>