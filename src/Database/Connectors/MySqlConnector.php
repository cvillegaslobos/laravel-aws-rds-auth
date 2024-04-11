<?php

namespace AWSRDSAuth\Database\Connectors;

use PDO;
use InvalidArgumentException;
use AWSRDSAuth\Auth\TokenProvider;
use Illuminate\Database\Connectors\MySqlConnector as MySqlConnectorBase;

class MySqlConnector extends MySqlConnectorBase
{
    /**
     * Create a new PDO Connection.
     *
     * @param  string  $dsn
     * @param  array  $config
     * @param  array  $options
     *
     * @return PDO
     *
     * @throws InvalidArgumentException when aws profile is not supplied
     */
    public function createConnection($dsn, array $config, array $options)
    {
        $tokenProvider = new TokenProvider($config);
        $config['password'] = $tokenProvider->getToken(); 

        return parent::createConnection($dsn, $config, $options);
    }
}