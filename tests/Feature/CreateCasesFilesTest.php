<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use Mudandstars\SyncEnumTypes\Actions\RelativePathAction;

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
    expect($enum1Content)->toContain("export const Enum1Cases: Array<Enum1> = [\n\t'first case',\n\t\"second's case\",\n];\n");

    $enum2Content = file_get_contents($typescriptDirPath.'/Enum2.ts');
    expect($enum2Content)->toContain("export const Enum2Cases: Array<Enum2> = [\n\t'first case second enum',\n\t'second case second enum',\n];\n");
});

it('properly imports the type', function () {
    Config::set('sync-enum-types.SYNC_CASES', true);
    Config::set('sync-enum-types.TYPESCRIPT_ENUM_FOLDER_DESTINATION', app_path('../resources/ts/types/Enum'));
    Config::set('sync-enum-types.CASES_FOLDER_DESTINATION', app_path('../resources/ts/EnumCases'));

    Artisan::call('sync-enum-types');

    $typesPath = config('sync-enum-types.TYPESCRIPT_ENUM_FOLDER_DESTINATION');
    $casesPath = config('sync-enum-types.CASES_FOLDER_DESTINATION');

    $importBasePath = RelativePathAction::execute($casesPath, $typesPath);

    $enum1Content = file_get_contents($casesPath.'/Enum1.ts');
    expect($enum1Content)->toContain("import { Enum1 } from '".$importBasePath."/Enum1';\n");

    $enum2Content = file_get_contents($casesPath.'/Enum2.ts');
    expect($enum2Content)->toContain("import { Enum2 } from '".$importBasePath."/Enum2';\n");
});

it('files are entirely correct', function () {
    Config::set('sync-enum-types.SYNC_CASES', true);
    Config::set('sync-enum-types.TYPESCRIPT_ENUM_FOLDER_DESTINATION', app_path('../resources/ts/types/Enum'));
    Config::set('sync-enum-types.CASES_FOLDER_DESTINATION', app_path('../resources/ts/EnumCases'));

    Artisan::call('sync-enum-types');

    $typesPath = config('sync-enum-types.TYPESCRIPT_ENUM_FOLDER_DESTINATION');
    $casesPath = config('sync-enum-types.CASES_FOLDER_DESTINATION');

    $importBasePath = RelativePathAction::execute($casesPath, $typesPath);

    $enum1Content = file_get_contents($casesPath.'/Enum1.ts');
    expect($enum1Content)->toContain("import { Enum1 } from '".$importBasePath."/Enum1';\nexport const Enum1Cases: Array<Enum1> = [\n\t'first case',\n\t\"second's case\",\n];\n");

    $enum2Content = file_get_contents($casesPath.'/Enum2.ts');
    expect($enum2Content)->toContain("import { Enum2 } from '".$importBasePath."/Enum2';\nexport const Enum2Cases: Array<Enum2> = [\n\t'first case second enum',\n\t'second case second enum',\n];\n");
});
