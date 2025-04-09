<?php

use Illuminate\Support\Facades\Route;
use Spatie\RouteDiscovery\Discovery\Discover;

Route::prefix('v1')->group(function () {
  Discover::controllers()->in(app_path('Http/Controllers/Api/V1'));
});