<?php

namespace Plokko\LaravelBulkgate;

use Illuminate\Support\ServiceProvider;
use Plokko\LaravelBulkgate\Channels\BulkgateSmsChannel;
use Plokko\LaravelBulkgate\Console\Commands\CheckNumbers;
use Plokko\LaravelBulkgate\Console\Commands\GetBalance;

class LaravelBulkgateServiceProvider extends ServiceProvider
{
    const config_name = 'laravel-bulkgate';

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config file //
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path(self::config_name.'.php'),
        ], self::config_name.':config');

        // Commands //
        if ($this->app->runningInConsole()) {
            $this->commands([
                GetBalance::class,
                CheckNumbers::class,
            ]);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // / Merge default config ///
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php',
            self::config_name
        );

        // / Facade interface ///
        $this->app->bind(BulkgateInterface::class, fn ($app) => new BulkgateInterface(config: $app->config->get(self::config_name, [])));

        // / Notification channel ///
        $this->app->singleton(BulkgateSmsChannel::class, fn ($app) => new BulkgateSmsChannel);
    }

    public function provides()
    {
        return [
            BulkgateApi::class,
            BulkgateSmsChannel::class,
        ];
    }
}
