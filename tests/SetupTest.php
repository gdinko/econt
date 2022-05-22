<?php

use Gdinko\Econt\Econt;

test('setup default econt object', function () {
    $econt = new Econt();

    expect($econt)->toBeInstanceOf(Econt::class);

    expect($econt->getAccount())->toMatchArray([
        'user' => config('econt.user'),
        'pass' => config('econt.pass'),
    ]);

    $defaultBaseUrl = config('econt.production-base-url');
    if (config('econt.env') == 'test') {
        $defaultBaseUrl = config('econt.test-base-url');
    }

    expect($econt->getBaseUrl())->toEqual($defaultBaseUrl);

    expect($econt->getTimeout())->toEqual(config('econt.timeout'));
});

test('set props of econt object', function () {
    $econt = new Econt();

    expect($econt)->toBeInstanceOf(Econt::class);

    $econt->setAccount('user', 'pass');
    expect($econt->getAccount())->toMatchArray([
        'user' => 'user',
        'pass' => 'pass',
    ]);

    $econt->setBaseUrl('endpoint');
    expect($econt->getBaseUrl())->toEqual('endpoint');

    $econt->setTimeout(99);
    expect($econt->getTimeout())->toEqual(99);
});

test('set test endpoint of econt object', function () {
    config(['econt.env' => 'test']);

    $econt = new Econt();

    expect($econt->getBaseUrl())->toEqual(config('econt.test-base-url'));
});

test('set production endpoint of econt object', function () {
    config(['econt.env' => 'production']);

    $econt = new Econt();

    expect($econt->getBaseUrl())->toEqual(config('econt.production-base-url'));
});
