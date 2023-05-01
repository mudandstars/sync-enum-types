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
            if (! is_dir($enumFilePath)) {
                $values = $this->getFileContents($enumFilesDir, $enumFilePath);

                $this->writeTypescriptFile($values, $enumFilePath);
            }
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
                    array_push($values, (new Substring($line))->between("'", "'"));
                }
            }

            fclose($file);
        }

        return $values;
    }

    private function writeTypescriptFile(array $values, string $filePath): void
    {
        $typeName = trim($filePath, '.php');

        $destinationPath = config('sync-enum-types.TYPESCRIPT_ENUM_FOLDER_DESTINATION').'/'.$typeName.'.d.ts';

        file_put_contents($destinationPath, $this->stubContents($typeName, $values));
    }

    public function stubContents(string $fileName, array $values): string
    {
        $contents = file_get_contents($this->STUB_PATH);

        foreach (['TYPE_NAME' => $fileName] as $search => $replace) {
            $contents = str_replace('{{ '.$search.' }}', $replace, $contents);
        }

        $valuesAsStrings = array_map(fn ($value) => "'".$value."'", $values);
        $possibleTypesString = implode(' | ', $valuesAsStrings);

        return preg_replace('/\r|\n|"""/', '', $contents).$possibleTypesString.';';
    }
}
