<?php

use AwsRdsAuth\TokenProvider;
use AwsRdsAuth\Connectors\PostgresConnector;

it('uses AWS token when IAM auth is true', function () {
    $config = [
        'username' => 'username',
        'password' => 'old-password',
        'use_iam_auth' => true
    ];

    $tokenProvider = $this->createMock(TokenProvider::class);
    $tokenProvider->method('token')->willReturn('new-password');

    $connector = $this->createPartialMock(PostgresConnector::class, ['createPdoConnection']);
    $connector->expects($this->once())->method('createPdoConnection')->with("dsn", $config['username'], 'new-password', []);

    $connector->createConnection("dsn", $config, [], $tokenProvider);
});

it('uses default password when IAM auth is false', function () {
    $config = [
        'username' => 'username',
        'password' => 'old-password',
        'use_iam_auth' => false
    ];

    $tokenProvider = $this->createMock(TokenProvider::class);

    $connector = $this->createPartialMock(PostgresConnector::class, ['createPdoConnection']);
    $connector->expects($this->once())->method('createPdoConnection')->with("dsn", $config['username'], $config['password'], []);

    $connector->createConnection("dsn", $config, [], $tokenProvider);
});

it('prepares the config for connection', function () {
    $tokenProvider = mock(TokenProvider::class);
    $connector = mock(PostgresConnector::class)
        ->makePartial()
        ->shouldAllowMockingProtectedMethods();

    $tokenProvider->shouldReceive('token')->andReturn('new-password');

    $updatedConfig = $connector->prepareConfig(['password' => 'old-password'], $tokenProvider);

    expect($updatedConfig['password'])->toBe('new-password');
});