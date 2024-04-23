<?php

use AwsRdsAuth\TokenProvider;
use AwsRdsAuth\Connectors\PostgresConnector;
use Illuminate\Database\Connectors\PostgresConnector as BasePostgresConnector;

it('makes a connection', function () {
    $tokenProvider = $this->createMock(TokenProvider::class);
    $tokenProvider->method('token')->willReturn('new-password');

    $baseConnector = $this->createMock(BasePostgresConnector::class);
    $baseConnector->expects($this->once())->method('connect')->with(['password' => 'new-password']);

    $connector = new PostgresConnector();
    $connector::swapBaseConnector($baseConnector);

    $connector->connect(['password' => 'old-password'], $tokenProvider);
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