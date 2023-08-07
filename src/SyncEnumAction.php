<?php

namespace Mudandstars\SyncEnumTypes;

use Mudandstars\SyncEnumTypes\Services\EnumFilesService;
use Mudandstars\SyncEnumTypes\Actions\SubstringBetweenAction;


abstract class SyncEnumAction
{
    protected string $stubPath;

    protected string $destinationFolder;

    public function execute(): void
    {
        foreach (EnumFilesService::all() as $enumFilePath) {
            $values = $this->getFileContents($enumFilePath);

            $this->writeTypescriptFile($values, $enumFilePath);
        }
    }

    private function getFileContents(string $filePath): array
    {
        $file = fopen($filePath, 'r');

        $values = [];

        if ($file) {
            while (($line = fgets($file)) !== false) {
                if (str_contains($line, 'case')) {
                    array_push($values, $this->correctValue($line));
                }
            }

            fclose($file);
        }

        return $values;
    }

    private function correctValue(string $line): string
    {
        if (str_contains($line, "';")) {
            return "'".SubstringBetweenAction::execute($line, "'", "'")."'";
        } else {
            return '"'.SubstringBetweenAction::execute($line, '"', '"').'"';
        }
    }

    private function writeTypescriptFile(array $values, string $filePath): void
    {
        if (! is_dir($this->destinationFolder)) {
            mkdir($this->destinationFolder);
        }

        $explodedFilePath = explode('/', $filePath);
        $typeName = trim($explodedFilePath[count($explodedFilePath) - 1], '.php');

        $destinationPath = $this->destinationFolder.'/'.$typeName.'.ts';

        file_put_contents($destinationPath, $this->stubContents($typeName, $values));
    }

    abstract public function stubContents(string $fileName, array $values): string;
}
