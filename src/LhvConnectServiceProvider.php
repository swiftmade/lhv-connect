<?php

namespace Swiftmade\LhvConnect;

use Illuminate\Support\ServiceProvider;

class LhvConnectServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        $this->handleConfigs();
    }

    public function register()
    {
        //
    }

    public function provides()
    {
        return [];
    }

    public function handleConfigs()
    {
        $configPath = __DIR__ . '/../../config/config.php';

        $this->publishes([
            $configPath => config_path('lhv-connect.php'),
        ]);
    }
}
