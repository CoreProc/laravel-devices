<?php

namespace Coreproc\Devices;

use Coreproc\Devices\Http\Middleware\StoreDevice;
use Illuminate\Support\ServiceProvider;

class DevicesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'migrations');

        // Publish config file
        $this->publishes([
            __DIR__ . '/../config/devices.php' => config_path('devices.php'),
        ], 'config');

        // Register middleware
        $router = $this->app['router'];

        $router->aliasMiddleware('store.device', StoreDevice::class);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/devices.php', 'devices');
    }
}
