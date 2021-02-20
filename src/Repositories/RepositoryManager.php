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
        return $this->container->config->get('ip_auth.default_repository', 'config');
    }

    /**
     * Create a configuration repository instance.
     *
     * @return \BoxedCode\Laravel\Auth\Ip\Repositories\ConfigRepository
     */
    protected function createConfigDriver()
    {
        $key = $this->container['config']->get(
            'ip_auth.repositories.config.key',
            'ip_auth'
        );

        return new ConfigRepository(
            $this->container['config']->get($key, [])
        );
    }

    /**
     * Create a database repository instance.
     *
     * @return \BoxedCode\Laravel\Auth\Ip\Repositories\DatabaseRepository
     */
    protected function createDatabaseDriver()
    {
        $config = $this->container['config']->get(
            'ip_auth.repositories.database'
        );

        $connection = $this->container['db']->connection(
            $config['connection']
        );

        return new DatabaseRepository(
            $connection,
            $config
        );
    }
}
