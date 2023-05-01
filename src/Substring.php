<?php

namespace Mudandstars\SyncEnumTypes;

class Substring
{
    public function __construct(private string $string)
    {
    }

    public function between(string $start, string $end): string
    {
        $ini = strpos($this->string, $start);

        if ($ini == 0) {
            return '';
        }

        $ini += strlen($start);

        $len = strpos($this->string, $end, $ini) - $ini;

        return substr($this->string, $ini, $len);
    }
}
