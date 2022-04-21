<?php

namespace Gdinko\Econt;

use Illuminate\Support\Facades\Http;
use Gdinko\Econt\Traits\Endpoints;
use Gdinko\Econt\Exceptions\EcontException;
use Gdinko\Econt\Hydrators\Address;

class Econt
{

    use Endpoints;

    /**
     * Econt API username
     */
    private $user;

    /**
     * Econt API password
     */
    private $pass;

    /**
     * Econt API endpoint
     */
    private $endpoint;

    /**
     * Econt API Request timeout
     */
    private $timeout;

    public function __construct()
    {
        $this->user = config('econt.user');

        $this->pass = config('econt.pass');

        $this->endpoint = config('econt.production-endpoint');

        if (config('econt.env') == 'test') {
            $this->endpoint = config('econt.test-endpoint');
        }

        $this->timeout = config('econt.timeout');
    }

    public function setAccount($user, $pass)
    {
        $this->user = $user;
        $this->pass = $pass;
    }

    public function getAccount()
    {
        return [
            'user' => $this->user,
            'pass' => $this->pass,
        ];
    }

    public function setEndpoint(string $endpoint)
    {
        $this->endpoint = rtrim($endpoint, '/');
    }

    public function getEndpoint()
    {
        return $this->endpoint;
    }

    public function setTimeout(int $timeout)
    {
        $this->timeout = $timeout;
    }

    public function getTimeout()
    {
        return $this->timeout;
    }

    public function request($endpoint, $request = [])
    {
        $response = Http::timeout($this->timeout)
            ->post($endpoint, $request)
            ->throw(function ($response, $e) {

                $message = $e->getMessage();
                $code = $e->getCode();
                $type = null;
                $fields = [];
                $errors = [];

                if ($response->json()) {
                    $message = $response->json()['type'] ?? $e->getMessage();
                    $type = $response->json()['type'] ?? null;
                    $fields = $response->json()['fields'] ?? null;
                    $errors = $response->json()['innerErrors'] ?? null;
                }

                throw new EcontException(
                    $message,
                    $code,
                    $type,
                    $fields,
                    $errors
                );
            });

        return $response->json();
    }

    /**
     * getCountries
     *
     * @return array
     */
    public function getCountries(): array
    {
        return $this->request(
            $this->getCountriesEndpoint()
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
        return $this->request(
            $this->getCitiesEndpoint(),
            ['countryCode' => $countryCode]
        )['cities'];
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
        return $this->request(
            $this->getOfficesEndpoint(),
            [
                'countryCode' => $countryCode,
                'cityID' => $cityID,
            ]
        )['offices'];
    }

    /**
     * getStreets
     *
     * @param  integer $cityID
     * @return array
     */
    public function getStreets(int $cityID = null): array
    {
        return $this->request(
            $this->getStreetsEndpoint(),
            ['cityID' => $cityID]
        )['streets'];
    }

    /**
     * getQuarters
     *
     * @param  integer $cityID
     * @return array
     */
    public function getQuarters(int $cityID = null): array
    {
        return $this->request(
            $this->getQuartersEndpoint(),
            ['cityID' => $cityID]
        )['quarters'];
    }

    public function validateAddress(Address $address)
    {
        return $this->request(
            $this->getValidateAddressEndpoint(),
            $address->validated()
        );
    }

    public function getNearestOffices()
    {
        return $this->request($this->getNearestOfficesEndpoint(), []);
    }
}
