<?php

use Mudandstars\SyncEnumTypes\Actions\SyncEnumTypesAction;

test('files have correct contents', function () {
    (new SyncEnumTypesAction())->execute();

    $typescriptDirPath = config('sync-enum-types.TYPESCRIPT_ENUM_FOLDER_DESTINATION');

    $enum1Content = file_get_contents($typescriptDirPath.'/Enum1.ts');
    expect($enum1Content)->toEqual("export type Enum1 ='first case' | \"second's case\";");

    $enum2Content = file_get_contents($typescriptDirPath.'/Enum2.ts');
    expect($enum2Content)->toEqual("export type Enum2 ='first case second enum' | 'second case second enum';");
});
