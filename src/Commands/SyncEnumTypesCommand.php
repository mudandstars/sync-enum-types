<?php

namespace Mudandstars\SyncEnumTypes\Commands;

use Illuminate\Console\Command;
use Mudandstars\SyncEnumTypes\SyncEnumTypesAction;

class SyncEnumTypesCommand extends Command
{
    public $signature = 'sync-enum-types';

    public $description = 'Updates your typescript Enum definitions in the destination folder specified by the configuration. Should run on save.';

    public function handle(): int
    {
        (new SyncEnumTypesAction)->execute();

        return self::SUCCESS;
    }
}
