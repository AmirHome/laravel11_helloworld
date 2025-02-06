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
cd laravel11
composer install
php artisan serve
```

## Step 2: Initialize a new package
the amirhome is namespace(typically your GitHub username or company name) and hello_world is package name

```bash
mkdir -p packages/amirhome/hello_world
cd packages/amirhome/hello_world
composer init

```

## Step 3: Create a service provider

```bash
touch src/HelloWorldServiceProvider.php
```
### first we need to create a empty service provider

```php
<?php
    namespace amirhome\HelloWorld;

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

### Read and check the laravel11/packages/amirhome/hello_world/composer.json file

```json
{
    "name": "amirhome/hello_world",
    "description": "My own laravel package",
    "autoload": {
        "psr-4": {
            "amirhome\\HelloWorld\\": "src/"
        }
    },
    "authors": [
        {
            "name": "amirhome",
            "email": "amir.email@yahoo.com"
        }
    ],
    "require": {},
    "extra": {
        "laravel": {
            "providers": [
                "amirhome\\HelloWorld\\HelloWorldServiceProvider"
            ]
        }
    }
}

```
### Run the following command to autoload the package

```bash
cd packages/amirhome/hello_world
composer dump-autoload
```

## Step 5: Register to Using Package in Development on local

### Add to laravel11/composer.json file

```json
"require": {
    "amirhome/hello_world": "@dev"
},
"repositories": [
    {
        "type": "path",
        "url": "packages/amirhome/hello_world"
    }
]
```

### Run the following command to install the package

```bash
cd laravel11
composer update
```

## Directory Package Structure

```bash
packages/
    amirhome/
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

### Create Routes
```php


```

## Controller Implementation

```bash
touch src/Http/Controllers/HelloWorldController.php
```

### Create Controller with index blade view
```php
<?php

namespace amirhome\HelloWorld\Http\Controllers;

use amirhome\HelloWorld\Models\HelloWorld;
use Illuminate\Contracts\View\View;

class HelloWorldController
{
    public function index(): View
    {
        $message = HelloWorld::first()->message ?? 'Hello World';
        
        return view('hello_world::index', compact('message'));
    }
}
```
### Create Blade View
```html
<!-- packages/amirhome/hello_world/resources/views/index.blade.php -->
<div>
    <h1>{{ $message }}</h1>
    <p>Package working successfully!</p>
</div> 

```

## Model with Migration

```bash
mkdir -p database/migrations
touch src/Models/HelloWorld.php
touch database/migrations/2024_01_01_000000_create_hello_world_table.php
```

### Create Model
```php
<?php

namespace amirhome\HelloWorld\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelloWorld extends Model
{
    use HasFactory;

    protected $fillable = ['message'];
}
```

### Create Migration
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hello_worlds', function (Blueprint $table) {
            $table->id();
            $table->string('message');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hello_worlds');
    }
};
```

### Update Routes File

```php
<?php

use Illuminate\Support\Facades\Route;
use amirhome\HelloWorld\Http\Controllers\HelloWorldController;

Route::prefix(config('hello_world.path'))
    ->middleware(config('hello_world.middleware'))
    ->group(function () {
        Route::get('/', [HelloWorldController::class, 'index']);
    });
```

### Update Service Provider

```php
class HelloWorldServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/hello_world.php' => config_path('hello_world.php'),
        ], 'hello_world_config');

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'hello_world_migrations');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/hello_world'),
        ], 'hello_world_views');
    }
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/hello_world.php', 'hello_world');
        $this->loadRoutesFrom(__DIR__ . '/Http/Routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'hello_world');

    }
}
```
### Run the following command to publish the config

```bash
php artisan vendor:publish --provider="amirhome\HelloWorld\HelloWorldServiceProvider"
```

## Install Package on Production

```bash
cd laravel11
composer update
php artisan vendor:publish --provider="amirhome\HelloWorld\HelloWorldServiceProvider"
php artisan migrate

php artisan serve

```

## Github Package link
https://github.com/AmirHome/hello_world

## Github example link
https://github.com/AmirHome/laravel11_helloworld







