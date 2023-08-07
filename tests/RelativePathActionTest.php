<?php

use Mudandstars\SyncEnumTypes\RelativePathAction;

it('returns correct path difference', function (string $fromPath, string $toPath, string $expectedResult) {
    expect(RelativePathAction::execute($fromPath, $toPath))->toBe($expectedResult);
})->with([
    ['resources/ts/EnumCases', 'resources/ts/types/Enum', '../types/Enum'],
    ['resources/ts/EnumCases/Test', 'resources/ts/types/Enum', '../../types/Enum'],
    ['resources/ts/EnumCases', 'resources/ts/types/Enum/Other', '../types/Enum/Other'],
]);
