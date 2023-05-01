<?php

namespace Mudandstars\SyncEnumTypes\Tests;

use Mudandstars\SyncEnumTypes\SyncEnumTypesServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->removeEnumFilesAndFiles();

        $this->createEnumFolders();
        $this->createEnumFilesInBackend();
    }

    protected function getPackageProviders($app)
    {
        return [
            SyncEnumTypesServiceProvider::class,
        ];
    }

    private function createEnumFolders(): void
    {
        mkdir(base_path('resources'));
        mkdir(base_path('resources/ts'));
        mkdir(base_path('resources/ts/types'));
        mkdir(config('sync-enum-types.PHP_ENUM_FOLDER_DESTINATION'));
        mkdir(config('sync-enum-types.TYPESCRIPT_ENUM_FOLDER_DESTINATION'));
    }

    private function createEnumFilesInBackend(): void
    {
        $firstEnumContents = file_get_contents(__DIR__.'/../src/stubs/enum1.stub');
        $secondEnumContents = file_get_contents(__DIR__.'/../src/stubs/enum2.stub');

        file_put_contents(config('sync-enum-types.PHP_ENUM_FOLDER_DESTINATION').'/Enum1.php', $firstEnumContents);
        file_put_contents(config('sync-enum-types.PHP_ENUM_FOLDER_DESTINATION').'/Enum2.php', $secondEnumContents);
    }

    private function removeEnumFilesAndFiles(): void
    {
        $typescriptDirPath = config('sync-enum-types.TYPESCRIPT_ENUM_FOLDER_DESTINATION');
        $phpDirPath = config('sync-enum-types.PHP_ENUM_FOLDER_DESTINATION');

        foreach ([$typescriptDirPath, $phpDirPath] as $dirPath) {
            foreach (scandir($dirPath) as $filePath) {
                $path = $dirPath.'/'.$filePath;

                if (! is_dir($path)) {
                    unlink($path);
                }
            }
        }

        rmdir($typescriptDirPath);
        rmdir($phpDirPath);
        rmdir(base_path('resources/ts/types'));
        rmdir(base_path('resources/ts'));
        rmdir(base_path('resources'));
    }
}
