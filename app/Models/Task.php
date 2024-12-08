<?php

namespace App\Models;

class Task
{
    public string $time;
    public string $command;

    /*public function __construct(string $time, string $command)
    {
        $this->time = $time;
        $this->command = $command;
    }*/
    public string $action;

    public function __construct(string $time, string $action, ?string $command = null)
    {
        $this->time = $time;
        $this->action = $action;
        $this->command = $command;
    }

    public function run(): string|false {
        $last_str = null;

        echo $this->command;
        try {
            exec($this->command);
        }
        catch(\Exception) {
            if (system(escapeshellcmd($this->command), $last_str)) {
                return $last_str;
            }
        }
        return false;
    }

}