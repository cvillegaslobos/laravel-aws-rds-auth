<?php

namespace AWSRDSAuth\Auth;

use Illuminate\Support\Arr;
use Aws\Rds\AuthTokenGenerator;
use Illuminate\Support\Facades\Cache;
use Aws\Credentials\CredentialProvider;

class TokenProvider
{
    /**
     * Database config
     *
     * @var array
     */
    protected $config;

    /**
     * @var AuthTokenGenerator
     */
    private $authTokenGenerator;

    /**
     * Class constructor
     *
     * @param  array  $config
     * @return void
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        $provider = CredentialProvider::defaultProvider();
        $this->authTokenGenerator = new AuthTokenGenerator($provider);
    }

    /**
     * Get the DBs Auth token from the Auth Token Generator
     *
     * @return string - Auth token
     */
    public function getToken()
    {
        return $this->authTokenGenerator->createToken(
            Arr::get($this->config, 'host').':'.Arr::get($this->config, 'port'),
            Arr::get($this->config, 'region'),
            Arr::get($this->config, 'username')
        );
    }
}