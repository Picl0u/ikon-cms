<?php

namespace Piclou\Ikcms;

use Illuminate\Support\ServiceProvider;
use Piclou\Ikcms\Console\IKCMSInstall;
use Piclou\Ikcms\Helpers\IkForm;
use Piclou\Ikcms\Middleware\AdministrationAccess;
use Piclou\Ikcms\Middleware\LangMiddleware;

class IkcmsServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'ikcms');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'ikcms');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/admin.php');
        $this->registerMiddleware();

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/ikcms.php', 'ikcms');

        $this->app->singleton('ikform', function($app){
            return new IkForm($app['form'], $app['session'], $app['ikcms']);
        });
        $this->app->singleton('ikcms', function ($app) {
            return new Ikcms;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['ikcms'];
    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/ikcms.php' => config_path('ikcms.php'),
        ], 'ikcms.config');

        // Publishing the views.
        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/piclou'),
        ], 'ikcms.views');

        // Publishing assets.
        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/piclou'),
        ], 'ikcms.views');

        // Publishing the translation files.
        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/piclou'),
        ], 'ikcms.views');

        // Registering package commands.
        $this->commands([
            IKCMSInstall::class
        ]);
    }

    /**
     * Register our middleware
     *
     * @return void
     */
    protected function registerMiddleware()
    {
        $this->app['router']->aliasMiddleware('ikcms.lang', LangMiddleware::class );
        $this->app['router']->aliasMiddleware('ikcms.admin', AdministrationAccess::class);
    }
}
