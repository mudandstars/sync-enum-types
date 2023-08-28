<?php

namespace Mudandstars\SyncEnumTypes\Actions;

class LinesBetweenBracesAction
{
    public static function execute(string $filePath, string $trigger): false|array
     {
        $lines = file($filePath);

        if (!$lines) {
            return false;
        }

        $capture = false;
        $results = [];

        foreach ($lines as $line) {
            $trimmed = trim($line);

            if (str_contains($trimmed, $trigger)) {
                $capture = true;
            }

            if ($capture && $trimmed !== "}") {
                $results[] = $trimmed;
            }

            if ($capture && $trimmed == "}") {
                $capture = false;
            }
        }

        return $results;
    }
}
