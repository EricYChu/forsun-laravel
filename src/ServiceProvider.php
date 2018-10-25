<?php

namespace Snower\LaravelForsun;

use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Laravel\Lumen\Application as LumenApplication;
use Snower\LaravelForsun\Commands\ScheduleRegisterCommand;
use Snower\LaravelForsun\Commands\ScheduleUnregisterCommand;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Boot the provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    protected function setupConfig(): void
    {
        $source = realpath(__DIR__ . '/config.php');

        if ($this->app instanceof LaravelApplication) {
            if ($this->app->runningInConsole()) {
                $this->publishes([
                    $source => config_path('forsun.php'),
                ]);
            }
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('forsun');
        }

        $this->mergeConfigFrom($source, 'forsun');
    }

    /**
     * Register the provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(Forsun::class, function ($app) {
            $forsun = new Forsun(config('forsun'));
            return $forsun;
        });

        $this->app->alias(Forsun::class, 'forsun');

        $this->commands([
            ScheduleRegisterCommand::class,
            ScheduleUnregisterCommand::class,
        ]);
    }

    /**
     * Get config value by key.
     *
     * @param string $key
     * @param mixed|null $default
     *
     * @return mixed
     */
    private function config($key, $default = null)
    {
        return $this->app->make('config')->get("forsun.{$key}", $default);
    }
}