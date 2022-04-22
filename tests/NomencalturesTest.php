<?php

use Gdinko\Econt\Facades\Econt;

test('Can Get Country list', function () {
    $countries = Econt::getCountries();

    expect($countries)
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

    $cities = Econt::getCities();

    expect($cities)
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

    $cities = Econt::getCities('bgr');

    expect($cities)
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

    $numCities = count($cities);
    $maxTryes = 5; //magic


    for ($i = 0; $i < $maxTryes; $i++) {
        $randomIndex = mt_rand(0, $numCities - 1);

        expect($cities[$randomIndex]['country']['code3'])->toBe('BGR');
    }
});
