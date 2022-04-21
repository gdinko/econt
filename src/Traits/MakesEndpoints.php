<?php

namespace Gdinko\Econt\Traits;

trait MakesEndpoints
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
}
