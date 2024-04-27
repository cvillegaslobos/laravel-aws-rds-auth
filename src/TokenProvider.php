<?php

namespace AwsRdsAuth;

use Aws\Credentials\CredentialProvider;
use Aws\Rds\AuthTokenGenerator;
use Illuminate\Support\Facades\Cache;

class TokenProvider
{
    /**
     * The cache key for the auth token.
     */
    public string $cacheKey = 'aws-rds-auth-token';

    /**
     * The cache time-to-live for the auth token.
     */
    public int $cacheTtl = 900;

    /**
     * The configuration array.
     */
    public array $config = [];

    /**
     * The auth token generator.
     */
    public AuthTokenGenerator $generator;

    /**
     * Create a new token provider instance.
     */
    public function __construct(array $config, AuthTokenGenerator $generator = null)
    {
        $this->config = $config;
        $this->generator = $generator ?? new AuthTokenGenerator(CredentialProvider::defaultProvider());
    }

    /**
     * Get the auth token.
     */
    public function token(): string
    {
        if ($this->config['token_cache_driver'] ?? null) {
            return $this->getTokenFromCache();
        }

        return $this->getTokenFromAws();
    }

    /**
     * Get the auth token from cache.
     */
    private function getTokenFromCache(): string
    {
        return Cache::store($this->config['token_cache_driver'])
            ->remember($this->cacheKey, $this->cacheTtl, function () {
                return $this->getTokenFromAws();
            });
    }

    /**
     * Get the auth token from AWS.
     */
    private function getTokenFromAws(): string
    {
        $endpoint = $this->config['host'].':'.$this->config['port'];
        $region = $this->config['region'] ?? null;
        $username = $this->config['username'] ?? null;

        return $this->generator->createToken($endpoint, $region, $username);
    }
}