<?php

namespace AWSRDSAuth\Database\Connectors;

use PDO;
use Exception;
use Throwable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use InvalidArgumentException;
use AWSRDSAuth\Auth\TokenProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Connectors\PostgresConnector as PostgresConnectorBase;

class PostgresConnector extends PostgresConnectorBase
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