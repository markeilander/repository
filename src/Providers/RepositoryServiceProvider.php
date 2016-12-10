<?php

namespace Eilander\Repository\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider.
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../Config/Repository.php' => config_path('repository.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__.'/../Config/Repository.php', 'repository'
        );

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'repository');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register('Eilander\Repository\Providers\EventServiceProvider');
    }
}
