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

    protected function tearDown(): void
    {
        $this->removeEnumFilesAndFiles();

        parent::tearDown();
    }

    protected function getPackageProviders($app)
    {
        return [
            SyncEnumTypesServiceProvider::class,
        ];
    }

    private function createEnumFolders(): void
    {
        mkdir(resource_path());
        mkdir(resource_path('ts'));
        mkdir(resource_path('ts/types'));
        mkdir(config('sync-enum-types.PHP_ENUM_FOLDER_DESTINATION'));
    }

    private function createEnumFilesInBackend(): void
    {
        foreach (['enum1', 'enum2', 'enumWithLinkedValue', 'enumWithDescriptionsInMethodWithFlag', 'enumWithDescriptionsInMethodWithoutFlag'] as $enumName) {
            file_put_contents(config('sync-enum-types.PHP_ENUM_FOLDER_DESTINATION').'/'.ucfirst($enumName).'.php', file_get_contents(__DIR__.'/../src/stubs/'.$enumName.'.stub'));
        }
    }

    private function removeEnumFilesAndFiles(): void
    {
        $typescriptDirPath = config('sync-enum-types.TYPESCRIPT_ENUM_FOLDER_DESTINATION');
        $phpDirPath = config('sync-enum-types.PHP_ENUM_FOLDER_DESTINATION');

        foreach ([$typescriptDirPath, $phpDirPath] as $dirPath) {
            if (! is_dir($dirPath)) {
                continue;
            }

            foreach (scandir($dirPath) as $filePath) {
                $path = $dirPath.'/'.$filePath;

                if (! is_dir($path)) {
                    unlink($path);
                }
            }
        }

        $this->removeDirectories([
            base_path('resources'),
            $phpDirPath,
        ]);
    }

    private function removeDirectories(array $paths): void
    {
        foreach ($paths as $path) {
            if (is_dir($path) && count(scandir($path)) > 2) {
                $childPaths = scandir($path);
                unset($childPaths[array_search('.', $childPaths)]);
                unset($childPaths[array_search('..', $childPaths)]);

                foreach ($childPaths as $childPath) {
                    if ($childPath === 'Enum') {
                    }
                    $this->removeFileOrDirectory($path.'/'.$childPath);
                }

                $this->removeFileOrDirectory($path);
            }

            if (is_dir($path)) {
                rmdir($path);
            }
        }
    }

    private function removeFileOrDirectory(string $path): void
    {
        if (is_dir($path)) {
            $this->removeDirectories([$path]);
        } else {
            unlink($path);
        }
    }
}
