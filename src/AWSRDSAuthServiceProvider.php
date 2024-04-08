<?php

namespace AWSRDSAuth;

use Illuminate\Support\ServiceProvider;
use AWSRDSAuth\Database\Connectors\ConnectionFactory;

class AWSRDSAuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->resolving('db', function ($db) {
            $db->extend('rds-pgsql', function ($config, $name) {
                $factory = new ConnectionFactory($this->app);

                return $factory->make($config, $name);                
            });
        });
    }
}
