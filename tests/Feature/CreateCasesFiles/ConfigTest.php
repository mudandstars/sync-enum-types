<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;

it('does not create any cases files if config is set to false', function () {
    Config::set('sync-enum-types.SYNC_CASES', false);
    Artisan::call('sync-enum-types');

    expect(is_dir(config('sync-enum-types.CASES_FOLDER_DESTINATION')))->toBeFalse();
});

it('does create the proper directory if the config is set to true', function () {
    Config::set('sync-enum-types.SYNC_CASES', true);
    Artisan::call('sync-enum-types');

    expect(is_dir(config('sync-enum-types.CASES_FOLDER_DESTINATION')))->toBeTrue();
});
