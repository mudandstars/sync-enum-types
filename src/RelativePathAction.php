<?php

namespace Mudandstars\SyncEnumTypes;

use Mudandstars\SyncEnumTypes\EnumFilesService;

class RelativePathAction
{
    public static function execute(string $fromPath, string $toPath): string
    {
        $path1 = explode('/', rtrim($fromPath, '/'));
        $path2 = explode('/', rtrim($toPath, '/'));

        $length1 = count($path1);
        $length2 = count($path2);

        $length = min($length1, $length2);
        $same_parts_length = $length;
        for($i = 0; $i < $length; $i++) {
            if($path1[$i] !== $path2[$i]) {
                $same_parts_length = $i;
                break;
            }
        }

        $relative_path = [];
        
        for($i = $same_parts_length; $i < $length1; $i++) {
            array_push($relative_path, '..');
        }

        for($i = $same_parts_length; $i < $length2; $i++) {
            array_push($relative_path, $path2[$i]);
        }

        return implode('/', $relative_path);
    }
}
