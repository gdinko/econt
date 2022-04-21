<?php

namespace Gdinko\Econt\Traits;

trait Endpoints
{
    public function getCountriesEndpoint()
    {
        return $this->endpoint . '/Nomenclatures/NomenclaturesService.getCountries.json';
    }

    public function getCitiesEndpoint()
    {
        return $this->endpoint . '/Nomenclatures/NomenclaturesService.getCities.json';
    }

    public function getOfficesEndpoint()
    {
        return $this->endpoint . '/Nomenclatures/NomenclaturesService.getOffices.json';
    }

    public function getStreetsEndpoint()
    {
        return $this->endpoint . '/Nomenclatures/NomenclaturesService.getStreets.json';
    }

    public function getQuartersEndpoint()
    {
        return $this->endpoint . '/Nomenclatures/NomenclaturesService.getQuarters.json';
    }

    public function getValidateAddressEndpoint()
    {
        return $this->endpoint . '/Nomenclatures/AddressService.validateAddress.json';
    }

    public function getNearestOfficesEndpoint()
    {
        return $this->endpoint . '/Nomenclatures/AddressService.getNearestOffices.json';
    }
}
