<?php

namespace AwsRdsAuth;

use AwsRdsAuth\Connectors\PostgresConnector;
use Illuminate\Support\ServiceProvider;

class RenameServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('db.connector.pgsql', function () {
            return new PostgresConnector();
        });
    }
}
