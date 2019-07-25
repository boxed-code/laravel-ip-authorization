<?php

namespace BoxedCode\Laravel\Auth\Ip\Contracts;

interface AuthManager
{
    const ACTION_ALLOW = 'allow';

    const ACTION_DENY = 'deny';

    const ADDRESS_WHITELISTED = 'address_whitelisted';

    const ADDRESS_BLACKLISTED = 'address_blacklisted';
}