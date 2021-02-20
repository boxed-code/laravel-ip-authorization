<?php

namespace BoxedCode\Laravel\Auth\Ip\Repositories;

use BoxedCode\Laravel\Auth\Ip\Contracts\Repository;
use IPTools\IP;
use IPTools\Range;
use Exception;

class ConfigRepository implements Repository
{
    /**
     * The configuration.
     * 
     * @var array
     */
    protected $config;

    /**
     * Create a new config repository instance.
     * 
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;

    }
    
    /**
     * Check whether an address of a give type exists within the given list.
     * 
     * @param  string $address 
     * @param  string $list    
     * @param  string $type    
     * @return bool
     */
    public function exists($address, $list, $type)
    {
        $ip = IP::parse($address);
        
        foreach ($this->all($list, $type) as $address) {
            if (Range::parse($address)->contains($ip)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add an entry to the repository.
     * 
     * @param  string $address
     * @param  string $list   
     * @param  string $type   
     * @return void
     */
    public function add($address, $list, $type)
    {
        throw new Exception(
            'The config repository does not support address manipulation.'
        );
    }

    /**
     * Delete an entry from the repository.
     * 
     * @param  string $address 
     * @param  string $list    
     * @param  string $type    
     * @return void   
     */
    public function delete($address, $list, $type)
    {
        throw new Exception(
            'The config repository does not support address manipulation.'
        );
    }

    /**
     * Get all of the entries of a given type within a given list.
     * 
     * @param  string $list
     * @param  string $type
     * @return array      
     */
    public function all($list, $type)
    {
        switch ($type)
        {
            case static::TYPE_ADDRESS:
                $key = 'addresses';
                break;

            default:
                throw new Exception(
                    sprintf(
                        'Invalid type specified. [%s]', 
                        $type
                    )
                );
        }

        return $this->config[$key][$list];
    }
}