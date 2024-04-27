<?php

use AwsRdsAuth\AwsRdsAuthServiceProvider;
use AwsRdsAuth\Connectors\PostgresConnector;

it('binds the pgsql connector', function () {
    $this->app->register(AwsRdsAuthServiceProvider::class);

    $connector = $this->app['db.connector.pgsql'];

    $this->assertInstanceOf(PostgresConnector::class, $connector);
});
