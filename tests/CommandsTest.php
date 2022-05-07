<?php

it('Can Sync Countries', function () {
    $this->artisan('sync:carrier-econt-countries')->assertExitCode(0);
});

it('Can Sync Offices', function () {
    $this->artisan('sync:carrier-econt-offices')->assertExitCode(0);
});

it('Can Sync Quarters', function () {
    $this->artisan('sync:carrier-econt-quarters')->assertExitCode(0);
});

it('Can Check API Status', function () {
    $this->artisan('get:carrier-econt-api-status')->assertExitCode(0);
});
