<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Mudandstars\SyncEnumTypes\Actions\SyncEnumTypesAction;

test('command creates typescript files in config location', function () {
    (new SyncEnumTypesAction())->execute();

    expect(count(scandir(config('sync-enum-types.TYPESCRIPT_ENUM_FOLDER_DESTINATION'))))->toBe(count(scandir(config('sync-enum-types.PHP_ENUM_FOLDER_DESTINATION'))));
});

test('files have correct contents', function () {
    (new SyncEnumTypesAction())->execute();

    $typescriptDirPath = config('sync-enum-types.TYPESCRIPT_ENUM_FOLDER_DESTINATION');

    $enum1Content = file_get_contents($typescriptDirPath.'/Enum1.ts');
    expect($enum1Content)->toEqual("export type Enum1 ='first case' | \"second's case\";");

    $enum2Content = file_get_contents($typescriptDirPath.'/Enum2.ts');
    expect($enum2Content)->toEqual("export type Enum2 ='first case second enum' | 'second case second enum';");
});

it('skips directories', function () {
    mkdir(config('sync-enum-types.PHP_ENUM_FOLDER_DESTINATION').'/directoryXYZ');

    (new SyncEnumTypesAction())->execute();

    expect(count(scandir(config('sync-enum-types.TYPESCRIPT_ENUM_FOLDER_DESTINATION'))))->toBe(count(scandir(config('sync-enum-types.PHP_ENUM_FOLDER_DESTINATION'))) - 1);
});

it('skips files from the exception config', function () {
    $exceptions = [
        'ExceptionEnum',
    ];
    Config::set('sync-enum-types.EXCEPTIONS', $exceptions);

    file_put_contents(config('sync-enum-types.PHP_ENUM_FOLDER_DESTINATION').'/'.$exceptions[0].'.php', 'some content');

    (new SyncEnumTypesAction())->execute();

    $syncedEnumFiles = scandir(config('sync-enum-types.TYPESCRIPT_ENUM_FOLDER_DESTINATION'));

    expect(in_array($exceptions[0].'.ts', $syncedEnumFiles))->toBeFalse();
});
