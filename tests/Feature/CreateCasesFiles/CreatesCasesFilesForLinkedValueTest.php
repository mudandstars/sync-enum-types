<?php

use Mudandstars\SyncEnumTypes\Actions\SyncEnumCasesAction;

it('assigns correct array to files', function () {
    (new SyncEnumCasesAction())->execute();

    $typescriptDirPath = config('sync-enum-types.CASES_FOLDER_DESTINATION');

    $enumWithLinkedValueContents = file_get_contents($typescriptDirPath.'/EnumWithLinkedValue.ts');
    expect($enumWithLinkedValueContents)->toContain("export const EnumWithLinkedValueCases: Array<EnumWithLinkedValue> = [\n\t'first case',\n];\n");
});
