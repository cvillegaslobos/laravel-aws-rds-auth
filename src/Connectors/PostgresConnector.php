<?php

namespace AwsRdsAuth\Connectors;

use AwsRdsAuth\TokenProvider;
use Illuminate\Database\Connectors\PostgresConnector as BasePostgresConnector;

class PostgresConnector extends BasePostgresConnector
{
    /**
     * Create a new PDO connection.
     *
     * @param  string  $dsn
     * @param  array  $config
     * @param  array  $options
     * @return \PDO
     *
     * @throws \Exception
     */
    public function createConnection($dsn, array $config, array $options, TokenProvider $provider = null)
    {
        if ($config['use_iam_auth'] ?? false) {
            $config = $this->prepareConfig($config, $provider ?? new TokenProvider($config));
        }

        return parent::createConnection($dsn, $config, $options);
    }

    /**
     * Swap the password with one provided by the token provider.
     *
     * @param  array $config
     * @param  TokenProvider $provider
     *
     * @return array
     */
    protected function prepareConfig(array $config, TokenProvider $provider): array
    {
        $config['password'] = $provider->token();

        return $config;
    }
}