<?php

use Mudandstars\SyncEnumTypes\Actions\SubstringBetweenAction;

it('returns correct substrings between', function (string $fullString, string $start, string $end, string $expected) {
    expect(SubstringBetweenAction::execute($fullString, $start, $end))->toBe($expected);
})->with([
    ['some x weirdx text', 'x', 'x', 'weird'],
    ['case CASE2 = some other', 'case', '=', 'CASE2'],
    ['self::CASE1 => 5,', 'self::', '=>', 'CASE1'],
]);
