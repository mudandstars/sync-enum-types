<?php

namespace Mudandstars\SyncEnumTypes;

class SyncEnumCasesAction extends SyncEnumAction
{
    protected string $stubPath = __DIR__.'/../src/stubs/typescript_cases.stub';

    public function __construct()
    {
        $this->destinationFolder = config('sync-enum-types.CASES_FOLDER_DESTINATION');
    }

    public function stubContents(string $fileName, array $values): string
    {
        $contents = file_get_contents($this->stubPath);

        foreach ([
            'NAME' => $fileName,
            'RELATIVE_BASE_PATH' => RelativePathAction::execute(config('sync-enum-types.CASES_FOLDER_DESTINATION'), config('sync-enum-types.TYPESCRIPT_ENUM_FOLDER_DESTINATION')),
        ] as $search => $replace) {
            $contents = str_replace('{{ '.$search.' }}', $replace, $contents);
        }

        $insert = "\n\t".implode(",\n\t", $values).',';

        $newStr = str_replace("\n\n", $insert."\n", $contents);

        return $newStr;
    }
}
