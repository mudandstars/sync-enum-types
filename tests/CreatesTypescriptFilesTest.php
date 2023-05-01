<?php

use Illuminate\Support\Facades\Artisan;

test('command creates typescript files in config location', function () {
    $FILE_BASECOUNT = 2;

    expect(count(scandir(config('sync-enum-types.TYPESCRIPT_ENUM_FOLDER_DESTINATION'))))->toBe($FILE_BASECOUNT);

    Artisan::call('sync-enum-types');

    expect(count(scandir(config('sync-enum-types.TYPESCRIPT_ENUM_FOLDER_DESTINATION'))))->toBe(count(scandir(config('sync-enum-types.TYPESCRIPT_ENUM_FOLDER_DESTINATION'))));
});

test('files have correct contents', function () {
    Artisan::call('sync-enum-types');

    $typescriptDirPath = config('sync-enum-types.TYPESCRIPT_ENUM_FOLDER_DESTINATION');

    $enum1Content = file_get_contents($typescriptDirPath.'/Enum1.d.ts');
    expect($enum1Content)->toEqual("export type Enum1 = 'first case' | 'second case';");

    $enum2Content = file_get_contents($typescriptDirPath.'/Enum2.d.ts');
    expect($enum2Content)->toEqual("export type Enum2 = 'first case second enum' | 'second case second enum';");
});
