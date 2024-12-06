<?php

namespace App\Models;
use PDO;
class Task
{
    public string $time;
    public string $command;

    public function __construct(string $time, string $command)
    {
        $this->time = $time;
        $this->command = $command;
    }

    public function toArray(): array
    {
        return [
            'time' => $this->time,
            'command' => $this->command,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self($data['time'], $data['command']);
    }

    public function run(): string|false {
        $laststring = null;
        if (system(escapeshellcmd($this->command),$laststring)) {
                return $laststring;
        }
        return false;
    }
}