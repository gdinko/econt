<?php

namespace Gdinko\Econt;

class Econt
{
    use MakesHttpRequests;
    use Actions\ManagesNomenclatures;

    /**
     * Econt API username
     */
    protected $user;

    /**
     * Econt API password
     */
    protected $pass;

    /**
     * Econt API Base Uri
     */
    protected $baseUri;

    /**
     * Econt API Request timeout
     */
    protected $timeout;

    public function __construct()
    {
        $this->user = config('econt.user');

        $this->pass = config('econt.pass');

        $this->timeout = config('econt.timeout');

        $this->configBaseUri();
    }

    public function configBaseUri()
    {
        $this->baseUri = config('econt.production-base-uri');

        if (config('econt.env') == 'test') {
            $this->baseUri = config('econt.test-base-uri');
        }
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

    public function setBaseUri(string $baseUri)
    {
        $this->baseUri = rtrim($baseUri, '/');
    }

    public function getBaseUri()
    {
        return $this->baseUri;
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
