<?php

namespace BoxedCode\Laravel\Auth\Ip\Repositories;

use BoxedCode\Laravel\Auth\Ip\Contracts\Repository;
use BoxedCode\Laravel\Auth\Ip\Contracts\RepositoryManager as RepositoryManagerContract;
use BoxedCode\Laravel\Auth\Ip\Repositories\ConfigRepository;
use Illuminate\Support\Manager;
use Net\Ip;

class RepositoryManager extends Manager implements RepositoryManagerContract
{
    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app->config->get('ipauth.repositories.default', 'config');
    }

    public function createConfigDriver()
    {
        return new ConfigRepository(
            $this->app['config']->get('ipauth', [])
        );
    }
}