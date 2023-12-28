# Laravel Econt API Wrapper

[![Latest Version on Packagist](https://img.shields.io/packagist/v/gdinko/econt.svg?style=flat-square)](https://packagist.org/packages/gdinko/econt)
[![Total Downloads](https://img.shields.io/packagist/dt/gdinko/econt.svg?style=flat-square)](https://packagist.org/packages/gdinko/econt)

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
php artisan vendor:publish --tag=econt-config
```

If you need to export migrations:

```bash
php artisan vendor:publish --tag=econt-migrations
```

If you need to export models:

```bash
php artisan vendor:publish --tag=econt-models
```

If you need to export commands:

```bash
php artisan vendor:publish --tag=econt-commands
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
Econt::setBaseUrl('endpoint');
Econt::setTimeout(99);
Econt::addAccountToStore('AccountUser', 'AccountPass');
Econt::getAccountFromStore('AccountUser');
Econt::setAccountFromStore('AccountUser');
```

Multiple Account Support In AppServiceProvider add accounts in boot method
```php
public function boot()
{
    Econt::addAccountToStore(
        'AccountUser',
        'AccountPass'
    );

    Econt::addAccountToStore(
        'AccountUser_XXX',
        'AccountPass_XXX'
    );
}
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
#sync countries with database (use -h to view options)
php artisan econt:sync-countries  

#sync cities with database (use -h to view options)
php artisan econt:sync-cities 

#create cities map with other carriers in database  (use -h to view options)
php artisan econt:map-cities

#sync offices with database (use -h to view options)
php artisan econt:sync-offices 

#sync querters with database (use -h to view options)
php artisan econt:sync-quarters 

#sync stretts with database (use -h to view options)
php artisan econt:sync-streets

#sync all nomenclatures with database (use -h to view options)
php artisan econt:sync-all

#get payments (use -h to view options)
php artisan econt:get-payments

#get econt api status (use -h to view options)
php artisan econt:api-status

#track parcels (use -h to view options)
php artisan econt:track
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
CarrierEcontTracking
CarrierCityMap
```

Events

```php
CarrierEcontTrackingEvent
CarrierEcontPaymentEvent
```

## Parcels Tracking

1. Subscribe to tracking event, you will recieve last tracking info, if tracking command is schduled

```php
Event::listen(function (CarrierEcontTrackingEvent $event) {
    echo $event->account;
    dd($event->tracking);
});
```

2. Before use of tracking command you need to create your own command and define setUp method

```bash
php artisan make:command TrackCarrierEcont
```

3. In app/Console/Commands/TrackCarrierEcont define your logic for parcels to be tracked

```php
use Gdinko\Econt\Commands\TrackCarrierEcontBase;

class TrackCarrierEcontSetup extends TrackCarrierEcontBase
{
    protected function setup()
    {
        //define parcel selection logic here
        // $this->parcels = [];
    }
}
```

4. Use the command

```bash
php artisan econt:track
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
-   [silabg.com](https://www.silabg.com/) :heart:
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.