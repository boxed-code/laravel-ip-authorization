<?php

namespace BoxedCode\Laravel\Auth\Ip;

use BoxedCode\Laravel\Auth\Ip\AuthManager;
use BoxedCode\Laravel\Auth\Ip\Contracts\AuthManager as AuthManagerContract;
use BoxedCode\Laravel\Auth\Ip\Contracts\RepositoryManager as RepoManagerContract;
use BoxedCode\Laravel\Auth\Ip\Repositories\RepositoryManager;
use Illuminate\Support\ServiceProvider;

class IpAuthServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->packagePath('config/ip_auth.php'), 'ip_auth');

        $this->registerRespositoryManager();

        $this->registerAuthManager();
    }

    /**
     * Register the repository manager within the container.
     * 
     * @return void
     */
    protected function registerRespositoryManager()
    {
        $this->app->bind(RepoManagerContract::class, function ($app) {
            return new RepositoryManager($app);
        });
    }

    /**
     * Register the authorization manager within the container.
     * 
     * @return void
     */
    protected function registerAuthManager()
    {
        $this->app->singleton(AuthManagerContract::class, function($app) {
            $repository = $app->make(RepoManagerContract::class);

            $config = $app->config->get('ip_auth', []);

            return (new AuthManager($repository, $config))
                ->setEventDispatcher($app['events']);
        });

        $this->app->alias(AuthManagerContract::class, 'auth.ip');
    }

    /**
     * Application is booting.
     *
     * @return void
     */
    public function boot()
    {
        // Register the package configuration to publish.
        $this->publishes(
            [$this->packagePath('config/ip_auth.php') => config_path('ip_auth.php')], 
            'config'
        );

        // Register the migrations to publish.
        $this->publishes(
            [$this->packagePath('migrations') => database_path('migrations')], 
            'migrations'
        );
    }

    /**
     * Loads a path relative to the package base directory.
     *
     * @param string $path
     * @return string
     */
    protected function packagePath($path = '')
    {
        return sprintf('%s/../%s', __DIR__, $path);
    }
}