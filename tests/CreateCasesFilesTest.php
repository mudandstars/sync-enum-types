<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

use function Pest\Laravel\withoutExceptionHandling;

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

it('creates the correct number of files in the correct location', function () {
    Config::set('sync-enum-types.SYNC_CASES', true);
    Artisan::call('sync-enum-types');

    expect(count(scandir(config('sync-enum-types.CASES_FOLDER_DESTINATION'))))->toBe(count(scandir(config('sync-enum-types.PHP_ENUM_FOLDER_DESTINATION'))));
});

it('assigns correct contents to files', function () {
    Config::set('sync-enum-types.SYNC_CASES', true);
    Artisan::call('sync-enum-types');

    $typescriptDirPath = config('sync-enum-types.CASES_FOLDER_DESTINATION');

    $enum1Content = file_get_contents($typescriptDirPath.'/Enum1.ts');
    expect($enum1Content)->toEqual("export const Enum1 = [\n\t'first case',\n\t\"second's case\",\n];\n");

    $enum2Content = file_get_contents($typescriptDirPath.'/Enum2.ts');
    expect($enum2Content)->toEqual("export const Enum2 = [\n\t'first case second enum',\n\t'second case second enum',\n];\n");
});
