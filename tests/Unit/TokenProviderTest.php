<?php

use AwsRdsAuth\TokenProvider;
use Aws\Rds\AuthTokenGenerator;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Facades\Cache;

it('retrieves token from cache', function () {
    $cacheStore = mock(Store::class);
    Cache::shouldReceive('store')->with('file')->andReturn($cacheStore);
    $cacheStore->shouldReceive('remember')->andReturn('cached-token');

    $provider = new TokenProvider([
        'token_cache_driver' => 'file',
    ]);

    $this->assertEquals('cached-token', $provider->token());
});

it('retrieves token from AWS when cache is not used', function () {
    $config = [
        'host' => 'localhost',
        'port' => 5432,
        'region' => 'us-east-1'
    ];

    $generator = mock(AuthTokenGenerator::class);
    $generator->shouldReceive('createToken')->andReturn('aws-token');

    $provider = new TokenProvider($config, $generator);

    $this->assertEquals('aws-token', $provider->token());
});

it('retrieves token from AWS when cache misses', function () {
    $config = [
        'host' => 'localhost',
        'port' => 5432,
        'region' => 'us-east-1',
        'token_cache_driver' => 'file'
    ];

    $generator = mock(AuthTokenGenerator::class);
    $generator->shouldReceive('createToken')->andReturn('aws-token');

    Cache::shouldReceive('store')->once()->with('file')->andReturnSelf();
    Cache::shouldReceive('remember')->once()->andReturnUsing(function ($key, $minutes, $callback) {
        return $callback();
    });

    $provider = new TokenProvider($config, $generator);

    $this->assertEquals('aws-token', $provider->token());
});