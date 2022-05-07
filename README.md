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

If you plan to use database for storing nomenclatures:

```bash
php artisan migrate
```

If you need to export configuration file:

```bash
php artisan vendor:publish --provider="gdinko\econt\EcontServiceProvider" --tag=config
```

## Configuration

```bash
ECONT_ENV=test|production #default=test
ECONT_API_USER= #default=iasp-dev
ECONT_API_PASS= #default=iasp-dev
ECONT_API_TEST_BASE_URI= #default=https://demo.econt.com/ee/services
ECONT_API_PRODUCTION_BASE_URI= #default=https://ee.econt.com/services
ECONT_API_TIMEOUT= #default=5
```

## Usage

Runtime Setup
```php
Econt::setAccount('user', 'pass');
Econt::setBaseUri('endpoint');
Econt::setTimeout(99);
```

Methods
```php

//Nomenclatures
Econt::getCountries();
Econt::getCities();
Econt::getOffices();
Econt::getStreets();
Econt::getQuarters();

//Labels
Econt::createLabel();
Econt::createLabels();
Econt::updateLabel();
Econt::deleteLabels();

//Misc
Econt::requestCourier();
Econt::getRequestCourierStatus();
Econt::getShipmentStatuses();
Econt::getClientProfiles();
Econt::paymentReport();
```

Commands

```bash
#sync countries with database
php artisan sync:carrier-econt-countries  

#sync cities with database
php artisan sync:carrier-econt-cities 

#sync offices with database
php artisan sync:carrier-econt-offices 

#sync querters with database
php artisan sync:carrier-econt-quarters 

#sync stretts with database
php artisan sync:carrier-econt-streets

#sync all nomenclatures with database
php artisan sync:carrier-econt-all

#get today payments
php artisan get:carrier-econt-payments

#get econt api status
php artisan get:carrier-econt-api-status
```

Models
```php
CarrierEcontCountry
CarrierEcontCity
CarrierEcontOffice
CarrierEcontStreet
CarrierEcontQuarter
CarrierEcontPayment
CarrierEcontApiStatus
```

## Examples

Address Validation
```php
try {
    $address = new Address([
        'city' => [
            'name' => 'София'
        ],
        'street' => 'България',
        'num' => '100'
    ]);

    dd(Econt::validateAddress($address));
} catch (EcontValidationException $eve) {
    echo $eve->getMessage();
    echo $eve->getCode();
    print_r($eve->getErrors());
} catch (EcontException $ee) {
    echo $ee->getMessage();
    echo $ee->getCode();
    print_r($ee->getErrors());
}
```

Get Nearest Offices to Address
```php
try {
    $address = new Address([
        'city' => [
            'name' => 'София'
        ],
        'street' => 'България',
        'num' => '100'
    ]);

    dd(Econt::getNearestOffices($address));
} catch (EcontValidationException $eve) {
    echo $eve->getMessage();
    echo $eve->getCode();
    print_r($eve->getErrors());
} catch (EcontException $ee) {
    echo $ee->getMessage();
    echo $ee->getCode();
    print_r($ee->getErrors());
}
```

Calculcate Price
```php
$labelData = [
    'senderClient' => [
        'name' => 'Иван Иванов',
        'phones' => [
            0 => '0888888888',
        ],
    ],
    'senderAddress' => [
        'city' => [
            'country' => [
                'code3' => 'BGR',
            ],
            'name' => 'София',
            'postCode' => 1000,
        ],
    ],
    'senderOfficeCode' => '1127',
    'receiverAddress' => [
        'city' => [
            'country' => [
                'code3' => 'BGR',
            ],
            'name' => 'София',
            'postCode' => 1000,
        ],
        'street' => 'България',
        'num' => '100',
    ],
    'packCount' => 1,
    'shipmentType' => ShipmentType::PACK,
    'weight' => 3.4,
    'shipmentDescription' => 'обувки',
    'services' => [
        'cdAmount' => 122.59,
        'cdType' => 'get',
        'cdCurrency' => 'BGN',
        'smsNotification' => true,
    ],
    'payAfterAccept' => false,
    'payAfterTest' => false,
];

$label = new Label(
    $labelData,
    LabelMode::CALCULATE
);

$result = Econt::createLabel($label);
```

Create Label
```php
$labelData = [
    'senderClient' => [
        'name' => 'Иван Иванов',
        'phones' => [
            0 => '0888888888',
        ],
    ],
    'senderAddress' => [
        'city' => [
            'country' => [
                'code3' => 'BGR',
            ],
            'name' => 'София',
            'postCode' => 1000,
        ],
    ],
    'senderOfficeCode' => '1127',
    'receiverClient' =>
    [
        'name' => 'Димитър Димитров',
        'phones' =>
        [
            0 => '0876543210',
        ],
    ],
    'receiverAddress' => [
        'city' => [
            'country' => [
                'code3' => 'BGR',
            ],
            'name' => 'София',
            'postCode' => '1000',
        ],
        'street' => 'България',
        'num' => 100,
    ],
    'packCount' => 1,
    'shipmentType' => ShipmentType::PACK,
    'weight' => 3.4,
    'shipmentDescription' => 'обувки',
    'services' => [
        'cdAmount' => '122.59',
        'cdType' => 'get',
        'cdCurrency' => 'BGN',
        'smsNotification' => true,
    ],
    'payAfterAccept' => false,
    'payAfterTest' => false,
    'holidayDeliveryDay' => 'workday',
];

$label = new Label(
    $labelData,
    LabelMode::CREATE
);

$result = Econt::createLabel($label);
```

Request Courier
```php
try {
    $curierRequest = [
        'requestTimeFrom' => '2022-05-05 16:00:00',
        'requestTimeTo' => '2022-05-05 17:00:00',
        'shipmentType' => 'PACK',
        'shipmentPackCount' => '1',
        'shipmentWeight' => '2',
        'senderClient' => [
            'name' => 'Иван Иванов',
            'phones' => [
                0 => '0888888888',
            ],
        ],
        'senderAddress' => [
            'city' => [
                'country' => [
                    'code3' => 'BGR',
                ],
                'postCode' => '7012',
                'name' => 'Русе',
            ],
            'fullAddress' => 'Алея Младост 7',
        ],
    ];

    dd(
        Econt::requestCourier(
            new Courier($curierRequest)
        )
    );
} catch (EcontValidationException $eve) {
    echo $eve->getMessage();
    echo $eve->getCode();
    print_r($eve->getErrors());
} catch (EcontException $ee) {
    echo $ee->getMessage();
    echo $ee->getCode();
    print_r($ee->getErrors());
}
```

Get Payments
```php
try {
    dd(
        Econt::paymentReport(new Payment([
            'dateFrom' => '2022-05-01',
            'dateTo' => '2022-05-05'
        ]))
    );
} catch (EcontValidationException $eve) {
    echo $eve->getMessage();
    echo $eve->getCode();
    print_r($eve->getErrors());
} catch (EcontException $ee) {
    echo $ee->getMessage();
    echo $ee->getCode();
    print_r($ee->getErrors());
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