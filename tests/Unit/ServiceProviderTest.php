<?php

use AwsRdsAuth\RenameServiceProvider;
use AwsRdsAuth\Connectors\PostgresConnector;

it('binds the pgsql connector', function () {
    $this->app->register(RenameServiceProvider::class);

    $connector = $this->app['db.connector.pgsql'];

    $this->assertInstanceOf(PostgresConnector::class, $connector);
});
