<?php

use Gdinko\Econt\Models\CarrierEcontCountry;

test('Country Model', function () {
    $this->artisan('econt:sync-countries');
    $this->artisan('econt:sync-offices');

    $data = CarrierEcontCountry::all();
    $this->assertNotEmpty($data);

    $data = CarrierEcontCountry::with(['offices'])->where('code3', 'BGR')->get();
    $this->assertNotEmpty($data);
});
