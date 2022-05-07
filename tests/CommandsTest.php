<?php

it('Can Sync Countries', function () {
    $this->artisan('econt:sync-countries')->assertExitCode(0);
});

it('Can Sync Offices', function () {
    $this->artisan('econt:sync-offices')->assertExitCode(0);
});

it('Can Sync Quarters', function () {
    $this->artisan('econt:sync-quarters')->assertExitCode(0);
});

it('Can Check API Status', function () {
    $this->artisan('econt:api-status')->assertExitCode(0);
});
