<?php

namespace BoxedCode\Laravel\Auth\Ip\Contracts;

interface Repository
{
    /**
     * Constant that represents the address entry type.
     */
    const TYPE_ADDRESS = 'address';

    /**
     * Check whether an address of a give type exists within the given list.
     * 
     * @param  string $address 
     * @param  string $list    
     * @param  string $type    
     * @return bool
     */
    public function exists($address, $list, $type);

    /**
     * Add an entry to the repository.
     * 
     * @param  string $address
     * @param  string $list   
     * @param  string $type   
     * @return void
     */
    public function add($address, $list, $type);

    /**
     * Delete an entry from the repository.
     * 
     * @param  string $address 
     * @param  string $list    
     * @param  string $type    
     * @return void   
     */
    public function delete($address, $list, $type);

    /**
     * Get all of the entries of a given type within a given list.
     * 
     * @param  string $list
     * @param  string $type
     * @return array      
     */
    public function all($list, $type);
}