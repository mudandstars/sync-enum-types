# This is my package sync-enum-types

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mudandstars/sync-enum-types.svg?style=flat-square)](https://packagist.org/packages/mudandstars/sync-enum-types)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/mudandstars/sync-enum-types/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/mudandstars/sync-enum-types/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/mudandstars/sync-enum-types.svg?style=flat-square)](https://packagist.org/packages/mudandstars/sync-enum-types)

This package ships a command that creates typescript declaration files for your php Enums.
This command can be set so it automatically runs on-save, for example.

## Installation

You can install the package via composer:

```bash
composer require mudandstars/sync-enum-types --dev
```


## Usage (VS Code)

1. Check if the published config needs changes, based on your project structure
2. Install the 'Run on Save' Extension
3. Add the 'Run on Save' command to your settings.json (I like the workspace settings for this):

```json
{
	...other settings

	"emeraldwalk.runonsave": {
		"commands": [
			{
				"match": ".*/Enum/.*\\.php$",
				"cmd": "php artisan sync-enum-types"
			}
		]
	}
}
```

## Configuration

You can publish the config file with:

```bash
php artisan vendor:publish --tag="sync-enum-types-config"
```

The recommended config has SYNC_CASES set to true to provide the enum cases as typed array for use in the frontend.
Its latest version looks like this:

```php
// config/sync-enum-types.php
return [
    'PHP_ENUM_FOLDER_DESTINATION' => app_path('Enum'),
    'TYPESCRIPT_ENUM_FOLDER_DESTINATION' => app_path('../resources/ts/types/Enum'),

    'SYNC_CASES' => true,
    'CASES_FOLDER_DESTINATION' => app_path('../resources/ts/EnumCases'),

    'EXCEPTIONS' => [],
]
```

## Possible Enum Features and Flags
### Using Custom Method's Description
To avoid conflicts with existing data when changing Enum values in your database, some engineers prefer to use simple integers as their values and make use of functions to describe what the values represent, like the following:
```php
enum MyEnum: int
{
    case FIRST_CASE = 1;
    case SECOND_CASE = 2;

    public function description(): string
    {
        return match($this) {
            self::FIRST_CASE => 'first case description',
            self::SECOND_CASE => 'second case description',
        };
    }

    public function someOtherFunction()...
}
```
If you want to sync the descriptions from an Enum's method instead of its cases' values, put the sync-using-method flag above the corresponding function like so:
```php
enum MyEnum: int
{
    case FIRST_CASE = 1;
    case SECOND_CASE = 2;

    // @sync-enum-types: sync-using-method
    public function description(): string
    {
        return match($this) {
            self::FIRST_CASE => 'first case description',
            self::SECOND_CASE => 'second case description',
        };
    }

    public function someOtherFunction()...
}
```
### Linking Other Enums
To maintain a single source of truth when using many Enums, sometimes it is necessary to define a case like this:
```php
    case MY_CASE = MyOtherEnum::ITS_CASE->value;
```
This is automatically handled correctly and resolves the value of the other Enum, as long as its source file also resides in the same directory.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Paul Sochiera](https://github.com/mudandstars)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
