<?php

namespace Gdinko\Econt\Traits;

use Illuminate\Support\Facades\Http;

trait NomenclaturesService
{
    /**
     * getCountries
     *
     * @return array
     */
    public function getCountries(): array
    {
        return Http::timeout($this->timeout)
            ->post($this->getCountriesEndpoint())
            ->json('countries');
    }

    /**
     * getCities
     *
     * @param string $countryCode Three-letter ISO Alpha-3 code of the country (e.g. AUT, BGR, etc.)
     * @return array
     */
    public function getCities(string $countryCode = ''): array
    {
        return Http::timeout($this->timeout)
            ->post($this->getCitiesEndpoint(), [
                'countryCode' => $countryCode,
            ])
            ->json('cities');
    }

    /**
     * getOffices
     *
     * @param  string $countryCode Three-letter ISO Alpha-3 code of the country (e.g. AUT, BGR, etc.)
     * @param  integer $cityID
     * @return array
     */
    public function getOffices(string $countryCode = '', int $cityID = null): array
    {
        return Http::timeout($this->timeout)
            ->post($this->getOfficesEndpoint(), [
                'countryCode' => $countryCode,
                'cityID' => $cityID,
            ])
            ->json('offices');
    }

    /**
     * getStreets
     *
     * @param  integer $cityID
     * @return array
     */
    public function getStreets(int $cityID = null): array
    {
        return Http::timeout($this->timeout)
            ->post($this->getStreetsEndpoint(), [
                'cityID' => $cityID,
            ])
            ->json('streets');
    }

    /**
     * getQuarters
     *
     * @param  integer $cityID
     * @return array
     */
    public function getQuarters(int $cityID = null): array
    {
        return Http::timeout($this->timeout)
            ->post($this->getQuartersEndpoint(), [
                'cityID' => $cityID,
            ])
            ->json('quarters');
    }
}
