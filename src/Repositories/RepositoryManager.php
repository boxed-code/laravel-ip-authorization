<?php

namespace BoxedCode\Laravel\Auth\Ip\Repositories;

use BoxedCode\Laravel\Auth\Ip\Contracts\RepositoryManager as ManagerContract;
use Illuminate\Support\Manager;

class RepositoryManager extends Manager implements ManagerContract
{
    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app->config->get('ip_auth.default_repository', 'config');
    }

    /**
     * Create a configuration repository instance.
     *
     * @return \BoxedCode\Laravel\Auth\Ip\Repositories\ConfigRepository
     */
    protected function createConfigDriver()
    {
        $key = $this->app['config']->get(
            'ip_auth.repositories.config.key',
            'ip_auth'
        );

        return new ConfigRepository(
            $this->app['config']->get($key, [])
        );
    }

    /**
     * Create a database repository instance.
     *
     * @return \BoxedCode\Laravel\Auth\Ip\Repositories\DatabaseRepository
     */
    protected function createDatabaseDriver()
    {
        $config = $this->app['config']->get(
            'ip_auth.repositories.database'
        );

        $connection = $this->app['db']->connection(
            $config['connection']
        );

        return new DatabaseRepository(
            $connection,
            $config
        );
    }
}
