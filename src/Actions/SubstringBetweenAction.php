<?php

namespace Mudandstars\SyncEnumTypes\Actions;

class SubstringBetweenAction
{
    public static function execute(string $fullString, string $start, string $end): string
    {
        $ini = strpos($fullString, $start);

        if ($ini == 0) {
            return '';
        }

        $ini += strlen($start);

        $len = strpos($fullString, $end, $ini) - $ini;

        return substr($fullString, $ini, $len);
    }
}
