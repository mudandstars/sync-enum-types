<?php

use Illuminate\Support\Facades\Config;
use Mudandstars\SyncEnumTypes\Actions\SyncEnumTypesAction;

test('command creates typescript files in config location', function () {
    (new SyncEnumTypesAction())->execute();

    expect(count(scandir(config('sync-enum-types.TYPESCRIPT_ENUM_FOLDER_DESTINATION'))))->toBe(count(scandir(config('sync-enum-types.PHP_ENUM_FOLDER_DESTINATION'))));
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
