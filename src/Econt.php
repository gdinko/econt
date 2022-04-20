<?php

namespace Gdinko\Econt;

use Illuminate\Support\Facades\Http;

class Econt
{
    private $user;

    private $pass;

    private $endpoint;

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

    public function setEndpoint($endpoint)
    {
        $this->endpoint = rtrim($endpoint, '/');
    }

    public function getEndpoint()
    {
        return $this->endpoint;
    }

    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * getCountries
     *
     * @return array
     */
    public function getCountries()
    {
        return Http::timeout($this->timeout)
            ->get("{$this->endpoint}/Nomenclatures/NomenclaturesService.getCountries.json")
            ->json('countries');
    }

    /**
     * getCities
     *
     * @param string $countryCode // Three-letter ISO Alpha-3 code of the country (e.g. AUT, BGR, etc.)
     * @return array
     */
    public function getCities($countryCode = '')
    {
        return Http::timeout($this->timeout)
            ->post("{$this->endpoint}/Nomenclatures/NomenclaturesService.getCities.json", [
                'countryCode' => $countryCode,
            ])
            ->json('cities');
    }
}
