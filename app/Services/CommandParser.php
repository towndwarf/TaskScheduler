<?php

namespace App\Services;

use Jenssegers\Date\Date;

class CommandParser
{
    public function parse(string $input): ?array
    {
        // Match "Write to DB in 15 minutes" or "Run 'command' at specific date/time"
        if (preg_match('/^(write to db|run \'([^\']+)\') (in (\d+ \w+)|at (.+))$/i', $input, $matches)) {
            $action = strtolower($matches[1]) === 'write to db' ? 'write_to_db' : 'run_command';
            $command = $matches[2] ?? null;

            // Parse "in 15 minutes"
            if (!empty($matches[4])) {
                $time = Date::now()->modify($matches[4]);
            }
            // Parse "at December 15, 2024"
            elseif (!empty($matches[5])) {
                $time = new Date($matches[5]);
            } else {
                return null;
            }

            return [
                'action' => $action,
                'command' => $command,
                'time' => $time->format('Y-m-d H:i:s'),
            ];
        }

        return null;
    }
}
