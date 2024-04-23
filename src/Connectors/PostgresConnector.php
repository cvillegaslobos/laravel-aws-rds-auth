<?php

namespace AwsRdsAuth\Connectors;

use AwsRdsAuth\TokenProvider;
use Illuminate\Database\Connectors\PostgresConnector as BasePostgresConnector;

class PostgresConnector extends BasePostgresConnector
{
    /**
     * The base connector swappable instance.
     */
    private static $baseConnector = null;

    /**
     * Establish a database connection.
     *
     * @param  array  $config
     * @return \PDO
     */
    public function connect(array $config, TokenProvider $provider = null)
    {
        $updatedConfig = $this->prepareConfig($config, $provider ?? new TokenProvider($config));

        return self::$baseConnector
            ? self::$baseConnector->connect($updatedConfig)
            : parent::connect($updatedConfig);
    }

    /**
     * Swap the base connector with a custom one.
     * Main purpose is to mock the base connector in tests.
     *
     * @param  BasePostgresConnector $connector
     *
     * @return BasePostgresConnector
     */
    public static function swapBaseConnector(BasePostgresConnector $connector)
    {
        self::$baseConnector = $connector;
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