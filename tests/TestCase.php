<?php

namespace AwsRdsAuth\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use AwsRdsAuth\RenameServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            RenameServiceProvider::class,
        ];
    }
}