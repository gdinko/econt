<?php

namespace Gdinko\Econt;

use Gdinko\Econt\Traits\MakesEndpoints;
use Gdinko\Econt\Traits\NomenclaturesService;

class Econt
{
    use MakesEndpoints;
    use NomenclaturesService;

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
}
