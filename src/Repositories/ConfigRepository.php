<?php

namespace BoxedCode\Laravel\Auth\Ip\Repositories;

use BoxedCode\Laravel\Auth\Ip\Contracts\Repository;
use Net\Ip;

class ConfigRepository implements Repository
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
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
        if (Ip::match($addressToMatch, $addresses)) {
            return true;
        }

        return false;
    }
}