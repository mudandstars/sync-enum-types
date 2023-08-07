<?php

namespace Mudandstars\SyncEnumTypes\Actions;

use Mudandstars\SyncEnumTypes\Contracts\SyncEnumAction;

class SyncEnumTypesAction extends SyncEnumAction
{
    protected string $stubPath = __DIR__.'/../../src/stubs/typescript_type.stub';

    public function __construct()
    {
        $this->destinationFolder = config('sync-enum-types.TYPESCRIPT_ENUM_FOLDER_DESTINATION');
    }

    public function stubContents(string $fileName, array $values): string
    {
        $contents = file_get_contents($this->stubPath);

        foreach (['NAME' => $fileName] as $search => $replace) {
            $contents = str_replace('{{ '.$search.' }}', $replace, $contents);
        }

        $possibleTypesString = implode(' | ', $values);

        return preg_replace('/\r|\n|"""/', '', $contents).$possibleTypesString.';';
    }
}
