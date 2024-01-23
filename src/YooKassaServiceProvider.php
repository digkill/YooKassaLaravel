<?php

namespace Digkill\YooKassaLaravel;

use Digkill\YooKassaLaravel\Contracts\Repositories\PaymentRepositoryInterface;
use Digkill\YooKassaLaravel\Repositories\PaymentRepository;
use Digkill\YooKassaLaravel\Services\PaymentService;
use Illuminate\Support\ServiceProvider;
use YooKassa\Client;

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

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }

        $this->publishes([
            __DIR__ . '/../config/yookassa.php' => config_path('yookassa.php'),
            __DIR__ . '/../resources/lang' => resource_path('yookassa.php'),
        ]);
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__ . '/../config/yookassa.php' => config_path('yookassa.php'),
        ], 'yookassa.config');

        // Publishing migrations
        $this->publishes([
            __DIR__ . '/../database/migrations' => base_path('database/migrations'),
        ], 'yookassa.migrations');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/yookassa.php', 'yookassa'
        );

        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);

        $this->app->bind(Client::class, function () {
            $client = new Client();
            $client->setAuth(config('yookassa.shop_id'), config('yookassa.secret_key'));
            return $client;
        });

        $this->app->singleton('Yookassa', function () {
            return new Yookassa(app(PaymentService::class));
        });

        $this->app->bind(YooKassa::class);

        $this->app->bind(PaymentService::class, function () {
            return new PaymentService(
                app(YooKassa::class),
                app(PaymentRepositoryInterface::class),
            );
        });

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return ['yookassa'];
    }
}
