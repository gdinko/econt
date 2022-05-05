# Laravel Econt API Wrapper

[![Latest Version on Packagist](https://img.shields.io/packagist/v/gdinko/econt.svg?style=flat-square)](https://packagist.org/packages/gdinko/econt)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/gdinko/econt/run-tests?label=tests)](https://github.com/gdinko/econt/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/gdinko/econt/Check%20&%20fix%20styling?label=code%20style)](https://github.com/gdinko/econt/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/gdinko/econt.svg?style=flat-square)](https://packagist.org/packages/gdinko/econt)
[![Test Coverage](https://raw.githubusercontent.com/gdinko/econt/master/badge-coverage.svg)](https://packagist.org/packages/gdinko/econt)


Laravel Econt API Wrapper

[Econt JSON API Documentation](http://ee.econt.com/services/)

## Installation

You can install the package via composer:

```bash
composer require gdinko/econt
```

## Configuration

```bash
ECONT_ENV=test|production #default=test
ECONT_API_USER= #default=iasp-dev
ECONT_API_PASS= #default=iasp-dev
```

## Usage

Get Countries
```php
dd(Econt::getCountries());
```

Get Cities
```php
try {
    dd(Econt::getCities('bgr'));
} catch (EcontException $ee) {
    echo $ee->getMessage();
    print_r($ee->getErrors());
    exit;
}
```

Get Offices
```php
try {
    dd(Econt::getOffices('bgr'));
} catch (EcontException $ee) {
    echo $ee->getMessage();
    print_r($ee->getErrors());
    exit;
}
```

Get Streets
```php
try {
    dd(Econt::getStreets(10));
} catch (EcontException $ee) {
    echo $ee->getMessage();
    print_r($ee->getErrors());
    exit;
}
```

Get Quarters
```php
try {
    dd(Econt::getQuarters(10));
} catch (EcontException $ee) {
    echo $ee->getMessage();
    print_r($ee->getErrors());
    exit;
}
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email dinko359@gmail.com instead of using the issue tracker.

## Credits

-   [Dinko Georgiev](https://github.com/gdinko)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.