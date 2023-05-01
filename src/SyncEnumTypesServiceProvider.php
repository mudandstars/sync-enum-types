<?php

namespace Mudandstars\SyncEnumTypes;

use Mudandstars\SyncEnumTypes\Commands\SyncEnumTypesCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SyncEnumTypesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('sync-enum-types')
            ->hasConfigFile()
            ->hasCommand(SyncEnumTypesCommand::class);
    }
}
