<?php

use Mudandstars\SyncEnumTypes\Actions\SyncEnumCasesAction;

it('does not use method if the file does not contain the flag', function () {
    (new SyncEnumCasesAction())->execute();

    $typescriptDirPath = config('sync-enum-types.CASES_FOLDER_DESTINATION');

    $enumWithDescriptionsInMethodWithoutFlag = file_get_contents($typescriptDirPath.'/EnumWithDescriptionsInMethodWithoutFlag.ts');
    expect($enumWithDescriptionsInMethodWithoutFlag)->toContain("export const EnumWithDescriptionsInMethodWithoutFlagCases: Array<EnumWithDescriptionsInMethodWithoutFlag> = [\n\t1,\n\t2,\n];\n");
});

it('does use method if the file contains the flag', function () {
    (new SyncEnumCasesAction())->execute();

    $typescriptDirPath = config('sync-enum-types.CASES_FOLDER_DESTINATION');

    $enumWithDescriptionsInMethodWithFlag = file_get_contents($typescriptDirPath.'/EnumWithDescriptionsInMethodWithFlag.ts');
    expect($enumWithDescriptionsInMethodWithFlag)->toContain("export const EnumWithDescriptionsInMethodWithFlagCases: Array<EnumWithDescriptionsInMethodWithFlag> = [\n\t'first case description',\n\t'second case description',\n];\n");
});
