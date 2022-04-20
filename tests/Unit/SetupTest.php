<?php

use Gdinko\Econt\Econt;

test('create default econt object', function () {
    $econt = new Econt();
    expect($econt)->toBeInstanceOf(Econt::class);
});

test('setup default econt object', function () {
    $econt = new Econt();

    expect($econt)->toBeInstanceOf(Econt::class);

    expect($econt->getAccount())->toMatchArray([
        'user' => config('econt.user'),
        'pass' => config('econt.pass'),
    ]);

    $defaultEndpoint = config('econt.production-endpoint');
    if (config('econt.env') == 'test') {
        $defaultEndpoint = config('econt.test-endpoint');
    }

    expect($econt->getEndpoint())->toEqual($defaultEndpoint);

    expect($econt->getTimeout())->toEqual(config('econt.timeout'));
});

test('setup props of econt object', function () {
    $econt = new Econt();

    expect($econt)->toBeInstanceOf(Econt::class);

    $econt->setAccount('user', 'pass');
    expect($econt->getAccount())->toMatchArray([
        'user' => 'user',
        'pass' => 'pass',
    ]);

    $econt->setEndpoint('endpoint');
    expect($econt->getEndpoint())->toEqual('endpoint');

    $econt->setTimeout(99);
    expect($econt->getTimeout())->toEqual(99);
});

test('set test endpoint of econt object', function () {
    config(['econt.env' => 'test']);

    $econt = new Econt();

    expect($econt->getEndpoint())->toEqual(config('econt.test-endpoint'));
});

test('set production endpoint of econt object', function () {
    config(['econt.env' => 'production']);

    $econt = new Econt();

    expect($econt->getEndpoint())->toEqual(config('econt.production-endpoint'));
});
