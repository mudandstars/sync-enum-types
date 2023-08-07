<?php

namespace Mudandstars\SyncEnumTypes\Services;

class EnumFilesService
{
    public static function all(): array
    {
        $enumFilesDir = config('sync-enum-types.PHP_ENUM_FOLDER_DESTINATION');

        $enumFiles = scandir(config('sync-enum-types.PHP_ENUM_FOLDER_DESTINATION'));

        if ($enumFiles === 2) {
            return [];
        }

        $filesWithoutExceptions = [];

        foreach ($enumFiles as $enumFilePath) {
            if (is_dir($enumFilesDir.'/'.$enumFilePath) || in_array(trim($enumFilePath, '.php'), config('sync-enum-types.EXCEPTIONS'))) {
                continue;
            }

            array_push($filesWithoutExceptions, $enumFilesDir.'/'.$enumFilePath);
        }

        return $filesWithoutExceptions;
    }
}
