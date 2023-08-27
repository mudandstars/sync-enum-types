<?php

namespace Mudandstars\SyncEnumTypes\Contracts;

use Mudandstars\SyncEnumTypes\Actions\SubstringBetweenAction;
use Mudandstars\SyncEnumTypes\Services\EnumFilesService;

abstract class SyncEnumAction
{
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
        $file = fopen($filePath, 'r');

        $casesToValues = [];

        if ($file) {
            while (($line = fgets($file)) !== false) {
                if (str_contains($line, 'case')) {
                    $caseName = trim(SubstringBetweenAction::execute($line, 'case', '='));

                    $casesToValues[$caseName] = $this->correctValue($line);
                }
            }

            fclose($file);
        }

        return $casesToValues;
    }

    private function correctValue(string $line): string
    {
        if (str_contains($line, "';")) {
            return "'".SubstringBetweenAction::execute($line, "'", "'")."'";
        } elseif (str_contains($line, '->value') && str_contains($line, '::')) {
            return $this->usedEnumValue($line);
        } else {
            return '"'.SubstringBetweenAction::execute($line, '"', '"').'"';
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
