<?php

namespace BoxedCode\Laravel\Auth\Ip\Repositories;

use BoxedCode\Laravel\Auth\Ip\Contracts\Repository;
use Illuminate\Database\ConnectionInterface as Connection;
use IPTools\Range;

class DatabaseRepository implements Repository
{
    /**
     * The database connection instance.
     *
     * @var \Illuminate\Database\ConnectionInterface
     */
    protected $connection;

    /**
     * The configuration.
     *
     * @var array
     */
    protected $config;

    /**
     * Create a new database repository instance.
     *
     * @param \Illuminate\Database\ConnectionInterface $connection
     * @param array                                    $config
     */
    public function __construct(Connection $connection, array $config)
    {
        $this->connection = $connection;

        $this->config = $config;
    }

    /**
     * Get the query builder instance for the configured table.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function getQuery()
    {
        return $this->connection->table(
            $this->config['table']
        );
    }

    /**
     * Check whether an address of a give type exists within the given list.
     *
     * @param string $address
     * @param string $list
     * @param string $type
     *
     * @return bool
     */
    public function exists($address, $list, $type)
    {
        $range = Range::parse($address);

        return $this->getQuery()
            ->where('type', '=', $type)
            ->where('list', '=', $list)
            ->where('range_start', '<=', $range->getFirstIp()->long)
            ->where('range_end', '>=', $range->getLastIp()->long)
            ->count() >= 1;
    }

    /**
     * Add an entry to the repository.
     *
     * @param string $address
     * @param string $list
     * @param string $type
     *
     * @return void
     */
    public function add($address, $list, $type)
    {
        $range = Range::parse($address);

        $this->getQuery()->insert([
            'type'        => $type,
            'list'        => $list,
            'label'       => $address,
            'range_start' => $range->getFirstIp()->long,
            'range_end'   => $range->getLastIp()->long,
        ]);
    }

    /**
     * Delete an entry from the repository.
     *
     * @param string $address
     * @param string $list
     * @param string $type
     *
     * @return void
     */
    public function delete($address, $list, $type)
    {
        $this->getQuery()
            ->where('type', '=', $type)
            ->where('list', '=', $list)
            ->where('label', '=', $address)
            ->delete();
    }

    /**
     * Get all of the entries of a given type within a given list.
     *
     * @param string $list
     * @param string $type
     *
     * @return array
     */
    public function all($list, $type)
    {
        return $this->getQuery()
            ->where('type', '=', $type)
            ->where('list', '=', $list)
            ->get()
            ->map(function ($item) {
                return $item->label;
            })->toArray();
    }
}
