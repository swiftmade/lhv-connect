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
        $this->mergeConfigFrom(
            __DIR__.'/../config/lhv-connect.php', 'lhv-connect'
        );
    }

    public function provides()
    {
        return [];
    }

    public function handleConfigs()
    {
        $configPath = __DIR__ . '/../config/lhv-connect.php';

        $this->publishes([
            $configPath => config_path('lhv-connect.php'),
        ]);
    }
}
