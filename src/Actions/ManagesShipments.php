<?php

namespace Gdinko\Econt\Actions;

use Gdinko\Econt\Hydrators\Courier;

trait ManagesShipments
{    
    /**
     * requestCourier
     *
     * @param  \Gdinko\Econt\Hydrators\Courier $courier
     * @return array
     * 
     * @throws Exception
     */
    public function requestCourier(Courier $courier): array
    {
        return $this->post(
            'Shipments/ShipmentService.requestCourier.json',
            $courier->validated(),
        );
    }
    
    /**
     * getRequestCourierStatus
     *
     * @param  array $requestCourierIds
     * @return array
     * 
     * @throws Exception
     */
    public function getRequestCourierStatus(array $requestCourierIds): array
    {
        return $this->post(
            'Shipments/ShipmentService.getRequestCourierStatus.json',
            ['requestCourierIds' => $requestCourierIds],
        );
    }
    
    /**
     * getShipmentStatuses
     *
     * @param  array $shipmentNumbers
     * @return array
     * 
     * @throws Exception
     */
    public function getShipmentStatuses(array $shipmentNumbers): array
    {
        return $this->post(
            'Shipments/ShipmentService.getShipmentStatuses.json',
            ['shipmentNumbers' => $shipmentNumbers],
        );
    }
}
