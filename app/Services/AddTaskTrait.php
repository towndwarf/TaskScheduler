<?php

namespace App\Services;
use App\Commands\AddCommand;
use App\Models\Task;
use Exception;
use JsonException;

trait AddTaskTrait
{
 public static function addTaskArguments(array $arguments): void {
     $input = implode(' ', array_slice($arguments, 2));
     $parser = new CommandParser();
     $parsed = $parser->parse($input);

     if ($parsed) {
         $task = new Task($parsed['time'], $parsed['action'], $parsed['command'] ?? null);
         $scheduler = new Scheduler();
         $scheduler->addTask($task);

         try {
             echo 'Task added successfully: ' . json_encode($parsed, JSON_THROW_ON_ERROR) . PHP_EOL;
         } catch (JsonException $exception) {
             echo 'Internal error while adding task ' . $exception->getTraceAsString() . PHP_EOL;
         }
     } else {
         try {

             array_shift($arguments);
             $ret_val = ((new AddCommand())->handle($arguments));
             echo '[' . ($ret_val['code'] < 0 ?'FAILED':'SCHEDULED') . ']: ' . $ret_val['msg'];
         } catch (Exception $exception) {
             echo '[FAILED]: ' . $exception->getMessage();
         }

     }
 }
}