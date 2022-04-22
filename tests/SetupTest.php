<?php

use Gdinko\Econt\Econt;

test('setup default econt object', function () {
    $econt = new Econt();

    expect($econt)->toBeInstanceOf(Econt::class);

    expect($econt->getAccount())->toMatchArray([
        'user' => config('econt.user'),
        'pass' => config('econt.pass'),
    ]);

    $defaultBaseUri = config('econt.production-base-uri');
    if (config('econt.env') == 'test') {
        $defaultBaseUri = config('econt.test-base-uri');
    }

    expect($econt->getBaseUri())->toEqual($defaultBaseUri);

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

    $econt->setBaseUri('endpoint');
    expect($econt->getBaseUri())->toEqual('endpoint');

    $econt->setTimeout(99);
    expect($econt->getTimeout())->toEqual(99);
});

test('set test endpoint of econt object', function () {
    config(['econt.env' => 'test']);

    $econt = new Econt();

    expect($econt->getBaseUri())->toEqual(config('econt.test-base-uri'));
});

test('set production endpoint of econt object', function () {
    config(['econt.env' => 'production']);

    $econt = new Econt();

    expect($econt->getBaseUri())->toEqual(config('econt.production-base-uri'));
});
