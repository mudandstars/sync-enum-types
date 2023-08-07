<?php

return [
    /*
        Set to the folder that contains your Enum files ending in '.php'.
        This should be your single source of truth.
        Will be synced to the frontend.
    */
    'PHP_ENUM_FOLDER_DESTINATION' => app_path('Enum'),

    /*
        Set to the folder that will contain your synced Enum files ending in '.ts'.
        This will be based off of your single source of truth set above.
        Results of the synchronization process.
    */
    'TYPESCRIPT_ENUM_FOLDER_DESTINATION' => app_path('../resources/ts/types/Enum'),

    /*
        If this flag is set to true, you also get synchronized Cases files ending in '.ts' in the specified location.
        This will be based off of your single source of truth set above.
        Contains an exported Array<YourEnumType>.
    */
    'SYNC_CASES' => true,
    'CASES_FOLDER_DESTINATION' => app_path('../resources/ts/EnumCases'),

    /*
        If you don't want that some of your Enum.php files are synced, specify them here.
        Just specify the name of the enum, such as 'MyEnum' (without '.php').
        Will be excluded from the synchronization process.
    */
    'EXCEPTIONS' => [],
];
