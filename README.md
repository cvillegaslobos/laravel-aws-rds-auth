# Laravel AWS RDS auth

This package is a thin wrapper around the default Laravel Postgres and MySQL database connection implementation. This will allow you to use [IAM database authentication](https://docs.aws.amazon.com/AmazonRDS/latest/UserGuide/UsingWithRDS.IAMDBAuth.html)

## Usage

This package will add two new connections drivers: `rds-pgsql` and `rds-mysql`, any configuration supported by `pgsql` and `mysql` is supported by the the `rds-*` implementation, with the exception of the `password` config which is overwriten by the AWS auth token.

In addition to the configurations the drivers support 2 new configs:
- 'region' - Which is the AWS region of the database you want to connect to
- 'password_cache_driver' - Which is [cache driver](https://laravel.com/docs/11.x/cache#driver-prerequisites) your application has configured, if you set this the password will be stored in the cache driver, so make sure your cache driver is secure enough for that.

The package uses the AWS [default credential provider](https://docs.aws.amazon.com/aws-sdk-php/v3/api/class-Aws.Credentials.CredentialProvider.html#_defaultProvider) so you can use this with any supported authentication method that AWS has.

## Quickstart

Add to your `config/database.php` alongside your other connections:

```
'rds-pgsql' => [
    'driver' => 'rds-pgsql',
    'url' => env('DB_URL'),
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '5432'),
    'database' => env('DB_DATABASE', 'laravel'),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => env('DB_CHARSET', 'utf8'),
    'prefix' => '',
    'prefix_indexes' => true,
    'search_path' => 'public',
    'region' => env('DB_AWS_REGION'),
    'password_cache_driver' => env('DB_PASSWORD_CACHE_DRIVER')
],
```

Update your envs:
- DB_CONNECTION=rds-pgsql
- DB_AWS_REGION=us-east-1
- DB_PASSWORD_CACHE_DRIVER=redis # Make sure your redis connection is secure!
