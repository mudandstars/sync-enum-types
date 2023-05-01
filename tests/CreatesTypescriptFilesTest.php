<?php

use Illuminate\Support\Facades\Artisan;

it('command creates typescript files in config location', function () {
    $FILE_BASECOUNT = 2;

    expect(count(scandir(config('sync-enum-types.TYPESCRIPT_ENUM_FOLDER_DESTINATION'))))->toBe($FILE_BASECOUNT);

    Artisan::call('sync-enum-types');

    expect(count(scandir(config('sync-enum-types.TYPESCRIPT_ENUM_FOLDER_DESTINATION'))))->toBe(count(scandir(config('sync-enum-types.TYPESCRIPT_ENUM_FOLDER_DESTINATION'))));
});
