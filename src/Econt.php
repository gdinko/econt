<?php

namespace Gdinko\Econt;

use Gdinko\Econt\Exceptions\EcontException;
use Illuminate\Support\Str;

class Econt
{
    use MakesHttpRequests;
    use Actions\ManagesNomenclatures;
    use Actions\ManagesLabels;
    use Actions\ManagesShipments;
    use Actions\ManagesProfile;
    use Actions\ManagesPaymentReports;

    public const SIGNATURE = 'CARRIER_ECONT';

    /**
     * Econt API username
     */
    protected $user;

    /**
     * Econt API password
     */
    protected $pass;

    /**
     * Econt API Account Store
     *
     * @var array
     */
    protected $accountStore = [];

    /**
     * Econt API Base Url
     */
    protected $baseUrl;

    /**
     * Econt API Request timeout
     */
    protected $timeout;

    public function __construct()
    {
        $this->user = config('econt.user');

        $this->pass = config('econt.pass');

        $this->timeout = config('econt.timeout');

        $this->configBaseUrl();
    }

    /**
     * configBaseUrl
     *
     * @return void
     */
    public function configBaseUrl()
    {
        $this->baseUrl = config('econt.production-base-url');

        if (config('econt.env') == 'test') {
            $this->baseUrl = config('econt.test-base-url');
        }
    }

    /**
     * setAccount
     *
     * @param  string $user
     * @param  string $pass
     * @return void
     */
    public function setAccount(string $user, string $pass)
    {
        $this->user = $user;
        $this->pass = $pass;
    }

    /**
     * getAccount
     *
     * @return array
     */
    public function getAccount(): array
    {
        return [
            'user' => $this->user,
            'pass' => $this->pass,
        ];
    }

    /**
     * getUser
     *
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * getSignature
     *
     * @return string
     */
    public function getSignature(): string
    {
        return self::SIGNATURE;
    }

    /**
     * setBaseUrl
     *
     * @param  string $baseUrl
     * @return void
     */
    public function setBaseUrl(string $baseUrl)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    /**
     * getBaseUrl
     *
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * setTimeout
     *
     * @param  int $timeout
     * @return void
     */
    public function setTimeout(int $timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * getTimeout
     *
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * addAccountToStore
     *
     * @param  string $user
     * @param  string $pass
     * @return void
     */
    public function addAccountToStore(string $user, string $pass)
    {
        $this->accountStore[Str::slug($user)] = [
            'user' => $user,
            'pass' => $pass,
        ];
    }

    /**
     * getAccountFromStore
     *
     * @param  string $user
     * @return array
     */
    public function getAccountFromStore(string $user): array
    {
        $key = Str::slug($user);

        if (isset($this->accountStore[$key])) {
            return $this->accountStore[$key];
        }

        throw new EcontException('Missing Account in Account Store');
    }

    /**
     * setAccountFromStore
     *
     * @param  string $account
     * @return void
     */
    public function setAccountFromStore(string $account)
    {
        $accountFromStore = $this->getAccountFromStore($account);

        $this->setAccount(
            $accountFromStore['user'],
            $accountFromStore['pass']
        );
    }
}
