<?php

use Gdinko\Econt\Enums\LabelMode;
use Gdinko\Econt\Enums\ShipmentType;
use Gdinko\Econt\Facades\Econt;
use Gdinko\Econt\Hydrators\Label;
use Gdinko\Econt\Hydrators\Labels;

it('Can Calculate Price', function () {
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

    expect($result)
        ->toBeArray()
        ->toHaveKeys([
            'label.totalPrice',
        ]);

    $this->assertIsNumeric($result['label']['totalPrice']);
});

it('Can Calculate Prices', function () {
    $labelsData = [
        [
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
                'num' => 100,
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
        ],
    ];

    $labels = new Labels(
        $labelsData,
        LabelMode::CALCULATE
    );

    $result = Econt::createLabels($labels);

    expect($result)
        ->toBeArray()
        ->toHaveKeys([
            '0.label.totalPrice',
        ]);

    $this->assertIsNumeric($result[0]['label']['totalPrice']);
});

it('Can Create Label', function () {
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

    $this->assertNotEmpty($result['label']['shipmentNumber']);
});

it('Can Update Label', function () {
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

    $this->assertNotEmpty($result['label']['shipmentNumber']);

    $labelData['shipmentNumber'] = $result['label']['shipmentNumber'];
    $labelData['services']['cdAmount'] = 300;

    $labelUpdate = new Label(
        $labelData,
        LabelMode::CREATE
    );

    $resultUpdate = Econt::updateLabel($labelUpdate);

    $this->assertNotEmpty($resultUpdate['label']['shipmentNumber']);

    $this->assertNotEquals(
        $result['label']['totalPrice'],
        $resultUpdate['label']['totalPrice']
    );
});

it('Can Delete Label', function () {
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

    $this->assertNotEmpty($result['label']['shipmentNumber']);

    $resultDelete = Econt::deleteLabels([
        $result['label']['shipmentNumber'],
    ]);

    $this->assertNull($resultDelete[0]['error']);

    $this->assertEquals(
        $resultDelete[0]['shipmentNum'],
        $result['label']['shipmentNumber']
    );
});

it('Can Check Shipment Status', function () {
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

    $this->assertNotEmpty($result['label']['shipmentNumber']);

    $resultStatus = Econt::getShipmentStatuses([
        $result['label']['shipmentNumber'],
    ]);

    $this->assertNull($resultStatus[0]['error']);

    $this->assertNotEmpty($resultStatus[0]['status']['trackingEvents']);
});
