<?php

use Illuminate\Support\Facades\Route;

Route::prefix(config('hello_world.path'))
    ->middleware(config('hello_world.middleware'))
    ->group(function () {
        Route::get('/', function () {
            return 'Hello World';
        });
    });
