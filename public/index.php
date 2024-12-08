<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Models\Task;
use App\Commands\AddCommand;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $time_offset = $_POST['time_offset'];
    $command = $_POST['command'];
    $schedule_time = date('Y-m-d H:i:s', strtotime($time_offset));

    $new_task = new Task($schedule_time, $command);
    $scheduler = new AddCommand();
    try {
        echo json_encode($scheduler->handle(['index.php', $schedule_time, $command]), JSON_THROW_ON_ERROR);
    } catch (JsonException $e) {
        echo $e->getTraceAsString();
    }
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