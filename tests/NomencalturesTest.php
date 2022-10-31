<?php

use Gdinko\Econt\Exceptions\EcontException;
use Gdinko\Econt\Exceptions\EcontValidationException;
use Gdinko\Econt\Facades\Econt;
use Gdinko\Econt\Hydrators\Address;

test('Can Get Country list', function () {
    $result = Econt::getCountries();

    expect($result)
        ->toBeArray()
        ->toHaveKeys([
            '0.id',
            '0.code2',
            '0.code3',
            '0.name',
            '0.nameEn',
            '0.isEU',
        ]);
});

test('Can Get City List', function () {
    Econt::setTimeout(50);

    $result = Econt::getCities();

    expect($result)
        ->toBeArray()
        ->toHaveKeys([
            '0.id',
            '0.country',
            '0.postCode',
            '0.name',
            '0.nameEn',
            '0.regionName',
            '0.regionNameEn',
            '0.phoneCode',
            '0.location',
            '0.expressCityDeliveries',
        ]);
});

test('Can Get City List For Bulgaria', function () {
    Econt::setTimeout(50);

    $result = Econt::getCities('bgr');

    expect($result)
        ->toBeArray()
        ->toHaveKeys([
            '0.id',
            '0.country',
            '0.postCode',
            '0.name',
            '0.nameEn',
            '0.regionName',
            '0.regionNameEn',
            '0.phoneCode',
            '0.location',
            '0.expressCityDeliveries',
        ]);

    $numCities = count($result);
    $maxTryes = 5; //magic


    for ($i = 0; $i < $maxTryes; $i++) {
        $randomIndex = mt_rand(0, $numCities - 1);

        expect($result[$randomIndex]['country']['code3'])->toBe('BGR');
    }
});

test('Can Get Office List For Bulgaria', function () {

    Econt::setTimeout(90);
    
    $result = Econt::getOffices('bgr');

    expect($result)
        ->toBeArray()
        ->toHaveKeys([
            '0.id',
            '0.code',
            '0.isMPS',
            '0.isAPS',
            '0.name',
            '0.nameEn',
            '0.phones',
            '0.emails',
            '0.address',
            '0.info',
            '0.currency',
            '0.language',
            '0.normalBusinessHoursFrom',
            '0.normalBusinessHoursTo',
            '0.halfDayBusinessHoursFrom',
            '0.halfDayBusinessHoursTo',
            '0.shipmentTypes',
            '0.partnerCode',
            '0.hubCode',
            '0.hubName',
            '0.hubNameEn',
        ]);
});

test('Can Get Street List For City', function () {
    $result = Econt::getStreets(10);

    expect($result)
        ->toBeArray()
        ->toHaveKeys([
            '0.id',
            '0.cityID',
            '0.name',
            '0.nameEn',
        ]);
});

test('Can Get Quarters List For City', function () {
    $result = Econt::getQuarters(10);

    expect($result)
        ->toBeArray()
        ->toHaveKeys([
            '0.id',
            '0.cityID',
            '0.name',
            '0.nameEn',
        ]);
});

test('Validate Address Valid Address', function () {
    $address = new Address([
        'city' => [
            'name' => 'София',
        ],
        'street' => 'България',
        'num' => '100',
    ]);

    $result = Econt::validateAddress($address);

    expect($result)
        ->toBeArray()
        ->toMatchArray([
            'validationStatus' => 'normal',
        ]);
});

test('Validate Address Invalid Address', function () {
    $address = new Address([
        'city' => [
            'name' => 'София',
        ],
        'street' => 'България',
    ]);

    expect(fn () => Econt::validateAddress($address))->toThrow(EcontValidationException::class);
});

test('Validate Address Valid Address Not Valida Data', function () {
    $address = new Address([
        'city' => [
            'name' => 'Никарагуа',
        ],
        'street' => 'България',
        'num' => '100',
    ]);

    expect(fn () => Econt::validateAddress($address))->toThrow(EcontException::class);
});

test('Get Nearest offices to address', function () {
    $address = new Address([
        'city' => [
            'name' => 'София',
        ],
        'street' => 'България',
        'num' => '100',
    ]);

    $result = Econt::getNearestOffices($address);

    expect($result)
        ->toBeArray()
        ->toHaveKeys([
            '0.id',
            '0.code',
            '0.isMPS',
            '0.isAPS',
            '0.name',
            '0.nameEn',
            '0.phones',
            '0.emails',
            '0.address',
            '0.info',
            '0.currency',
            '0.language',
            '0.normalBusinessHoursFrom',
            '0.normalBusinessHoursTo',
            '0.halfDayBusinessHoursFrom',
            '0.halfDayBusinessHoursTo',
            '0.shipmentTypes',
            '0.partnerCode',
            '0.hubCode',
            '0.hubName',
            '0.hubNameEn',
        ]);
});
