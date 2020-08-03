<?php

namespace ErikAraujo\HttpLogger;

use Illuminate\Support\ServiceProvider;

class HttpLoggerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/http-logger.php' => config_path('http-logger.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../database/migrations/logs.stub' => database_path(
                    sprintf('migrations/%s_create_logs_table.php', date('Y_m_d_His'))
                ),
            ], 'migrations');
        }

        $this->app->singleton(LogWriter::class, config('http-logger.log_writer'));
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/http-logger.php', 'http-logger');
    }
}
