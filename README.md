<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Package Development in Laravel 11

## Step 1: Create a new Laravel project

```bash
composer create-project laravel/laravel laravel11
```

## Step 2: Initialize a new package
the amirhoss is namespace(typically your GitHub username or company name) and hello_world is package name

```bash
mkdir -p packages/amirhoss/hello_world
cd packages/amirhoss/hello_world
composer init

```

## Step 3: Create a service provider

```bash
touch src/HelloWorldServiceProvider.php
```
### first we need to create a empty service provider

```php
<?php
    namespace Amirhoss\HelloWorld;

    use Illuminate\Support\ServiceProvider;

    class HelloWorldServiceProvider extends ServiceProvider
    {
        public function register()
        {
            // Register package services
        }

        public function boot()
        {
            // Bootstrap any package services
        }
    }
```

## Step 4: Autoloading

### Read and check the laravel11/packages/amirhoss/hello_world/composer.json file

```json
{
    "name": "amirhoss/hello_world",
    "description": "My own laravel package",
    "autoload": {
        "psr-4": {
            "Amirhoss\\HelloWorld\\": "src/"
        }
    },
    "authors": [
        {
            "name": "AmirHome",
            "email": "amir.email@yahoo.com"
        }
    ],
    "require": {},
    "extra": {
        "laravel": {
            "providers": [
                "Amirhoss\\HelloWorld\\HelloWorldServiceProvider"
            ]
        }
    }
}

```
### Run the following command to autoload the package

```bash
composer dump-autoload
```

## Step 5: Register to Using Package in Development on local

### Add to laravel11/composer.json file

```json
"require": {
    "amirhoss/hello_world": "@dev"
},
"repositories": [
    {
        "type": "path",
        "url": "packages/amirhoss/hello_world"
    }
]
```

### Run the following command to install the package

```bash
composer update
```

### Run the following command to install the package

```bash
composer update
```

## Directory Package Structure

```bash
packages/
    amirhoss/
        hello_world/
            config/
                hello_world.php
            database/
                migrations/
                    create_hello_world_table.php
                seeders/
            resources/
                views/
                    hello_world.blade.php
            src/
                Helper/
                    helpers.php
                Http/
                    Controllers/
                        HelloWorldController.php
                    Routes/
                        web.php
                Models/
                    HelloWorld.php
                Traits/
            HelloWorldServiceProvider.php
            composer.json
```

## Step 1: Create Package Config

```bash
mkdir -p config
touch config/hello_world.php
```

```php
<?php

return [
    'path' => 'hello',
    'middleware' => ['web'],
];
```

## Step 2: Create Package Routes

```bash
mkdir -p src/Http/Routes
touch src/Http/Routes/web.php
```

```php
<?php

use Illuminate\Support\Facades\Route;

Route::prefix(config('hello_world.path'))
    ->middleware(config('hello_world.middleware'))
    ->group(function () {
        Route::get('/', function () {
            return 'Hello World';
        });
    });
```

