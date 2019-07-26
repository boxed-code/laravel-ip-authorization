<?php

namespace BoxedCode\Laravel\Auth\Ip\Contracts;

interface RepositoryManager
{
    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver();

    /**
     * Get a driver instance.
     *
     * @param  string  $driver
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function driver($driver = null);

    /**
     * Register a custom driver creator Closure.
     *
     * @param  string    $driver
     * @param  \Closure  $callback
     * @return $this
     */
    public function extend($driver, Closure $callback);

    /**
     * Get all of the created "drivers".
     *
     * @return array
     */
    public function getDrivers();
}