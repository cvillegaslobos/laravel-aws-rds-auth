<?php

namespace AWSRDSAuth\Auth;

use Illuminate\Support\Arr;
use Aws\Rds\AuthTokenGenerator;
use Illuminate\Cache\DatabaseStore;
use Illuminate\Support\Facades\Log;
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
     * Database cache key
     *
     * @var string
     */
    protected $databaseCacheKey;

    /**
     * Database cache key
     *
     * @var string
     */
    protected $databaseCacheTTL = 900;

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
    public function getToken(bool $force = false)
    {
        $cacheDriver = Arr::get($this->config, 'password_cache_driver');

        // Password cache is disabled
        if (is_null($cacheDriver)) {
            return $this->requestToken();
        }

        $cache = Cache::store($cacheDriver);

        // We need to check if the user provided the database as it's own password cache driver
        if($cache->getStore() instanceof DatabaseStore) {
            $cacheConfig = collect($cache->getStore()->getConnection()->getConfig());
            $diff = $cacheConfig->diff(collect($this->config));

            if ($diff->isEmpty()) {
                Log::warning("The cache driver used for the password is the same as the database connection. Skipping password caching because this can cause a deadlock.");
                return $this->requestToken();
            }
        }

        if ($force) {
            $cache->forget($this->databaseCacheKey);
        }

        return $cache->remember($this->databaseCacheKey, $this->databaseCacheTTL, function () {
            return $this->requestToken(); 
        });
    }

    public function requestToken(){
        return $this->authTokenGenerator->createToken(
            Arr::get($this->config, 'host').':'.Arr::get($this->config, 'port'),
            Arr::get($this->config, 'region'),
            Arr::get($this->config, 'username')
        );
    }
}