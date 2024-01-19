<?php

namespace Digkill\YooKassaLaravel;

use Illuminate\Support\ServiceProvider;

/**
 * Bootstrap any package services.
 *
 * @return void
 */
class YooKassaServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'Digkill\\YooKassaLaravel');

        $this->publishes([
            __DIR__ . '/../config/yookassa.php' => config_path('yookassa.php'),
            __DIR__ . '/../resources/lang' => resource_path('yookassa.php'),
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/yookassa.php', 'yookassa'
        );

        $this->app->bind(YooKassa::class);
    }

}
