<?php

use Gdinko\Econt\Facades\Econt;
use Gdinko\Econt\Hydrators\Payment;

it('Can Get Client Profiles', function () {

    $result = Econt::getClientProfiles();

    $this->assertNotEmpty($result[0]['client']);
});

it('Can Get Payment Report', function () {

    $result = Econt::paymentReport(new Payment([
        'dateFrom' => date('Y-m-d'),
        'dateTo' => date('Y-m-d')
    ]));

    $this->assertIsArray($result);
});
