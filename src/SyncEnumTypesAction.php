<?php

namespace Mudandstars\SyncEnumTypes;

class SyncEnumTypesAction
{
    private string $STUB_PATH = __DIR__.'/../src/stubs/typescript.stub';

    public function execute(): void
    {
        $enumFilesDir = config('sync-enum-types.PHP_ENUM_FOLDER_DESTINATION');

        $enumFiles = scandir(config('sync-enum-types.PHP_ENUM_FOLDER_DESTINATION'));

        if ($enumFiles === 2) {
            return;
        }

        foreach ($enumFiles as $enumFilePath) {
            if (is_dir($enumFilesDir.'/'.$enumFilePath) || in_array(trim($enumFilePath, '.php'), config('sync-enum-types.EXCEPTIONS'))) {
                continue;
            }

            $values = $this->getFileContents($enumFilesDir, $enumFilePath);

            $this->writeTypescriptFile($values, $enumFilePath);
        }
    }

    private function getFileContents(string $dirPath, string $filePath): array
    {
        $path = $dirPath.'/'.$filePath;

        $file = fopen($path, 'r');

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
            return "'".(new Substring($line))->between("'", "'")."'";
        } else {
            return '"'.(new Substring($line))->between('"', '"').'"';
        }
    }

    private function writeTypescriptFile(array $values, string $filePath): void
    {
        $folderDestination = config('sync-enum-types.TYPESCRIPT_ENUM_FOLDER_DESTINATION');

        if (! is_dir($folderDestination)) {
            mkdir($folderDestination);
        }

        $typeName = trim($filePath, '.php');

        $destinationPath = $folderDestination . '/'.$typeName.'.ts';

        file_put_contents($destinationPath, $this->stubContents($typeName, $values));
    }

    public function stubContents(string $fileName, array $values): string
    {
        $contents = file_get_contents($this->STUB_PATH);

        foreach (['TYPE_NAME' => $fileName] as $search => $replace) {
            $contents = str_replace('{{ '.$search.' }}', $replace, $contents);
        }

        $possibleTypesString = implode(' | ', $values);

        return preg_replace('/\r|\n|"""/', '', $contents).$possibleTypesString.';';
    }
}
