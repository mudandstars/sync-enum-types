<?php

use Mudandstars\SyncEnumTypes\Actions\SyncEnumTypesAction;

test('uses the method to sync the types when the flag is set', function () {
    (new SyncEnumTypesAction())->execute();

    $typescriptDirPath = config('sync-enum-types.TYPESCRIPT_ENUM_FOLDER_DESTINATION');

    $enumWithDescriptionInMethodWithFlagContents = file_get_contents($typescriptDirPath.'/EnumWithDescriptionsInMethodWithFlag.ts');
    expect($enumWithDescriptionInMethodWithFlagContents)->toEqual("export type EnumWithDescriptionsInMethodWithFlag ='first case description' | 'second case description';");
});

test('uses the cases to sync the types when the flag is not set', function () {
    (new SyncEnumTypesAction())->execute();

    $typescriptDirPath = config('sync-enum-types.TYPESCRIPT_ENUM_FOLDER_DESTINATION');

    $enumWithDescriptionInMethodWithoutFlagContents = file_get_contents($typescriptDirPath.'/EnumWithDescriptionsInMethodWithoutFlag.ts');
    expect($enumWithDescriptionInMethodWithoutFlagContents)->toEqual('export type EnumWithDescriptionsInMethodWithoutFlag =1 | 2;');
});
