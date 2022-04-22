<?php

namespace Gdinko\Econt\Actions;

use Gdinko\Econt\Hydrators\Address;

trait ManagesNomenclatures
{
    /**
     * getCountries
     *
     * @return array
     */
    public function getCountries(): array
    {
        return $this->get(
            'Nomenclatures/NomenclaturesService.getCountries.json'
        )['countries'];
    }

    /**
     * getCities
     *
     * @param string $countryCode Three-letter ISO Alpha-3 code of the country (e.g. AUT, BGR, etc.)
     * @return array
     */
    public function getCities(string $countryCode = ''): array
    {
        return $this->post(
            'Nomenclatures/NomenclaturesService.getCities.json',
            ['countryCode' => $countryCode]
        )['cities'];
    }

    /**
     * getOffices
     *
     * @param  string $countryCode Three-letter ISO Alpha-3 code of the country (e.g. AUT, BGR, etc.)
     * @param  int $cityID
     * @return array
     */
    public function getOffices(string $countryCode = '', int $cityID = null): array
    {
        return $this->post(
            'Nomenclatures/NomenclaturesService.getOffices.json',
            [
                'countryCode' => $countryCode,
                'cityID' => $cityID,
            ]
        )['offices'];
    }

    /**
     * getStreets
     *
     * @param  int $cityID
     * @return array
     */
    public function getStreets(int $cityID = null): array
    {
        return $this->post(
            'Nomenclatures/NomenclaturesService.getStreets.json',
            ['cityID' => $cityID]
        )['streets'];
    }

    /**
     * getQuarters
     *
     * @param  int $cityID
     * @return array
     */
    public function getQuarters(int $cityID = null): array
    {
        return $this->post(
            'Nomenclatures/NomenclaturesService.getQuarters.json',
            ['cityID' => $cityID]
        )['quarters'];
    }

    /**
     * validateAddress
     *
     * @param \Gdinko\Econt\Hydrators\Address $address
     * @return array
     */
    public function validateAddress(Address $address): array
    {
        return $this->post(
            'Nomenclatures/AddressService.validateAddress.json',
            $address->validated()
        );
    }

    /**
     * getNearestOffices
     *
     * @param \Gdinko\Econt\Hydrators\Address $address
     * @return array
     */
    public function getNearestOffices(Address $address): array
    {
        return $this->post(
            'Nomenclatures/AddressService.getNearestOffices.json',
            $address->validated()
        )['offices'];
    }
}
