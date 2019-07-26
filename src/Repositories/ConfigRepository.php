<?php

namespace BoxedCode\Laravel\Auth\Ip\Repositories;

use BoxedCode\Laravel\Auth\Ip\Contracts\Repository;
use IPTools\IP;
use IPTools\Network;
use IPTools\Range;

class ConfigRepository implements Repository
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function whitelistAddress($address)
    {
        
    }

    public function blacklistAddress($address)
    {
        
    }

    public function isWhitelistedAddress($address)
    {
        $addresses = $this->config['whitelist'];

        if ($this->matchAddress($address, $addresses)) {
            return true;
        }

        return false;
    }

    public function isBlacklistedAddress($address)
    {
        $addresses = $this->config['blacklist'];

        if ($this->matchAddress($address, $addresses)) {
            return true;
        }

        return false;
    }

    protected function matchAddress($addressToMatch, array $addresses)
    {
        $ip = IP::parse($addressToMatch);
        
        foreach ($addresses as $address) {
            if (Range::parse($address)->contains($ip)) {
                return true;
            }
        }

        return false;
    }
}