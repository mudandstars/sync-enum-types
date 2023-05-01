# This is my package sync-enum-types

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mudandstars/sync-enum-types.svg?style=flat-square)](https://packagist.org/packages/mudandstars/sync-enum-types)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/mudandstars/sync-enum-types/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/mudandstars/sync-enum-types/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/mudandstars/sync-enum-types.svg?style=flat-square)](https://packagist.org/packages/mudandstars/sync-enum-types)

This package ships a command that creates typescript declaration files for your php Enums.
This command can be set so it automatically runs on-save, for example.

## Installation

You can install the package via composer:

```bash
composer require mudandstars/sync-enum-types
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="sync-enum-types-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="sync-enum-types-config"
```

This is the contents of the published config file:

```php
return [
];
```


## Usage to run on-save in VS Code

Use the emeraldwalk 'Run on Save' Extension and have the 'sync-enum-types' command execute on-save.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Paul Sochiera](https://github.com/mudandstars)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
