<?php

namespace BoxedCode\Laravel\Auth\Ip;

use BoxedCode\Laravel\Auth\Ip\AuthManager;
use BoxedCode\Laravel\Auth\Ip\Contracts\AuthManager as ManagerContract;
use BoxedCode\Laravel\Auth\Ip\Contracts\RepositoryManager as RepositoryManagerContract;
use BoxedCode\Laravel\Auth\Ip\Repositories\RepositoryManager;
use Illuminate\Support\ServiceProvider;
use Torann\GeoIP\GeoIP;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->packagePath('config/ipauth.php'), 'ipauth');

        $this->app->bind(RepositoryManagerContract::class, function ($app) {
            return new RepositoryManager($app);
        });

        $this->app->singleton(ManagerContract::class, function($app) {
            $repository = $app->make(RepositoryManagerContract::class);

            $config = $app->config->get('ipauth');

            return new AuthManager($repository, $config);
        });

        $this->app->alias(ManagerContract::class, 'auth.ip');
    }

    /**
     * Application is booting.
     *
     * @return void
     */
    public function boot()
    {
        //
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