<?php

namespace Gdinko\Econt\Actions;

use Gdinko\Econt\Hydrators\Courier;

trait ManagesShipments
{
    /**
     * requestCourier
     *
     * @param  \Gdinko\Econt\Hydrators\Courier $courier
     * @return string
     *
     */
    public function requestCourier(Courier $courier): string
    {
        return $this->post(
            'Shipments/ShipmentService.requestCourier.json',
            $courier->validated(),
        )['courierRequestID'];
    }

    /**
     * getRequestCourierStatus
     *
     * @param  array $requestCourierIds
     * @return array
     *
     */
    public function getRequestCourierStatus(array $requestCourierIds): array
    {
        return $this->post(
            'Shipments/ShipmentService.getRequestCourierStatus.json',
            ['requestCourierIds' => $requestCourierIds],
        )['requestCourierStatus'];
    }

    /**
     * getShipmentStatuses
     *
     * @param  array $shipmentNumbers
     * @return array
     *
     */
    public function getShipmentStatuses(array $shipmentNumbers): array
    {
        return $this->post(
            'Shipments/ShipmentService.getShipmentStatuses.json',
            ['shipmentNumbers' => $shipmentNumbers],
        )['shipmentStatuses'];
    }
}
