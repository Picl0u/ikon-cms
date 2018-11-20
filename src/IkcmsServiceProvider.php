<?php

namespace Piclou\Ikcms;

use Illuminate\Support\ServiceProvider;
use Piclou\Ikcms\Console\IKCMSInstall;
use Piclou\Ikcms\Helpers\IkForm;
use Piclou\Ikcms\Middleware\AdministrationAccess;
use Piclou\Ikcms\Middleware\LangMiddleware;
use Piclou\Ikcms\Middleware\MaintenanceMiddleware;

class IkcmsServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Views
        if(glob(resource_path('views/vendor/piclou/*'))) {
            $this->loadViewsFrom(resource_path('views/vendor/piclou'), 'ikcms');
        }else {
            $this->loadViewsFrom(__DIR__.'/../resources/views', 'ikcms');
        }
        // Translations
        if(glob(resource_path('lang/vendor/piclou/*'))) {
            $this->loadTranslationsFrom(resource_path('lang/vendor/piclou'), 'ikcms');
        }else {
            $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'ikcms');
        }
        // Routes
        if(glob(base_path('routes/vendor/piclou/*'))) {
            $this->loadRoutesFrom(base_path('routes/vendor/piclou/admin.php'));
        }else {
            $this->loadRoutesFrom(__DIR__.'/../routes/admin.php');
        }

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
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
            __DIR__.'/../resources/views' => resource_path('views/vendor/piclou'),
        ], 'ikcms.views');

        // Publishing assets.
        $this->publishes([
            __DIR__.'/../resources/assets' => resource_path('assets/vendor/piclou'),
        ], 'ikcms.assets');

        // Publishing the translation files.
        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/piclou'),
        ], 'ikcms.lang');

        // Publishing the route file
        $this->publishes([
            __DIR__.'/../routes' => base_path('routes/vendor/piclou'),
        ], 'ikcms.routes');

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
        $this->app['router']->aliasMiddleware('ikcms.maintenance', MaintenanceMiddleware::class );
        $this->app['router']->aliasMiddleware('ikcms.admin', AdministrationAccess::class);
    }
}
