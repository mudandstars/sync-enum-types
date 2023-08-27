<?php

use Mudandstars\SyncEnumTypes\Actions\SyncEnumTypesAction;

test('files have correct contents', function () {
    (new SyncEnumTypesAction())->execute();

    $typescriptDirPath = config('sync-enum-types.TYPESCRIPT_ENUM_FOLDER_DESTINATION');

    $enum1Content = file_get_contents($typescriptDirPath.'/EnumWithLinkedValue.ts');
    expect($enum1Content)->toEqual("export type EnumWithLinkedValue ='first case';");
});
