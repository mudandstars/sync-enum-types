<?php

namespace Mudandstars\SyncEnumTypes\Contracts;

use Mudandstars\SyncEnumTypes\Actions\LinesBetweenBracesAction;
use Mudandstars\SyncEnumTypes\Actions\SubstringBetweenAction;
use Mudandstars\SyncEnumTypes\Services\EnumFilesService;

abstract class SyncEnumAction
{
    const SYNC_USING_METHOD_TRIGGER = '// @sync-enum-types: sync-using-method';

    protected string $stubPath;

    protected string $destinationFolder;

    public function execute(): void
    {
        foreach (EnumFilesService::all() as $enumFilePath) {
            $casesToValues = $this->getFileContents($enumFilePath);

            $this->writeTypescriptFile($casesToValues, $enumFilePath);
        }
    }

    private function getFileContents(string $filePath): array
    {
        $casesToValues = [];

        $usesCustomMethod = str_contains(file_get_contents($filePath), self::SYNC_USING_METHOD_TRIGGER);

        $lines = $usesCustomMethod
            ? LinesBetweenBracesAction::execute($filePath, self::SYNC_USING_METHOD_TRIGGER)
            : file($filePath);

        if ($lines) {
            foreach ($lines as $line) {
                if ($usesCustomMethod && str_contains($line, 'self::')) {
                    $caseName = SubstringBetweenAction::execute($line, 'self::', '=>');

                    $casesToValues[$caseName] = $this->correctValueDescriptionInForMethod($line);
                } elseif (! $usesCustomMethod && str_contains($line, 'case') && str_contains($line, 'case') && str_contains($line, ';')) {
                    $caseName = SubstringBetweenAction::execute($line, 'case', '=');

                    $casesToValues[$caseName] = $this->correctValueForDescriptionInCase($line);
                }
            }
        }

        return $casesToValues;
    }

    private function correctValueForDescriptionInCase(string $line): string
    {
        if (str_contains($line, '->value') && str_contains($line, '::')) {
            return $this->usedEnumValue($line);
        } else {
            return trim(SubstringBetweenAction::execute($line, '=', ';'));
        }
    }

    private function correctValueDescriptionInForMethod(string $line): string
    {
        if (str_contains($line, ',')) {
            return trim(SubstringBetweenAction::execute($line, '=>', ','));
        } else {
            return trim(SubstringBetweenAction::execute($line, '=>', "\n"));
        }
    }

    private function usedEnumValue(string $line): string
    {
        $usedEnumName = trim(SubstringBetweenAction::execute($line, '=', '::'));
        $usedEnumCase = trim(SubstringBetweenAction::execute($line, '::', '->value'));

        $usedEnumCasesToValues = $this->getFileContents(config('sync-enum-types.PHP_ENUM_FOLDER_DESTINATION').'/'.$usedEnumName.'.php');

        return $usedEnumCasesToValues[$usedEnumCase];
    }

    private function writeTypescriptFile(array $casesToValues, string $filePath): void
    {
        if (! is_dir($this->destinationFolder)) {
            mkdir($this->destinationFolder);
        }

        $explodedFilePath = explode('/', $filePath);
        $typeName = trim($explodedFilePath[count($explodedFilePath) - 1], '.php');

        $destinationPath = $this->destinationFolder.'/'.$typeName.'.ts';

        file_put_contents($destinationPath, $this->stubContents($typeName, $casesToValues));
    }

    abstract public function stubContents(string $fileName, array $casesToValues): string;
}
