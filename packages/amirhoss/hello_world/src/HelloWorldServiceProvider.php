<?php

namespace Amirhoss\HelloWorld;

use Illuminate\Support\ServiceProvider;

class HelloWorldServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/hello_world.php' => config_path('hello_world.php'),
        ], 'hello_world_config');
    }
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/hello_world.php', 'hello_world');
        $this->loadRoutesFrom(__DIR__ . '/Http/Routes/web.php');
        

    }
}
