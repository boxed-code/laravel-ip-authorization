<?php

namespace BoxedCode\Laravel\Auth\Ip;

use BoxedCode\Laravel\Auth\Ip\Contracts\AuthManager as ManagerContract;
use BoxedCode\Laravel\Auth\Ip\Contracts\RepositoryManager;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use InvalidArgumentException;

class AuthManager implements ManagerContract
{
    protected $repositories;

    protected $config;

    protected $dispatcher;

    public function __construct(RepositoryManager $repositories, array $config)
    {
        $this->repositories = $repositories;

        $this->config = $config;
    }

    public function authorize(Request $request)
    {
        if ($this->validate($request)) {
            $this->event(new Events\Authorized($request));
            return true;
        }

        $this->event(new Events\Denied($request));
        return false;
    }

    public function validate(Request $request)
    {
        $address = $this->resolveAddressFromRequest($request);

        foreach ($this->config['directives'] as $directive => $action) {
            // If the given address is listed within the list specified 
            // by the directive with check the action type associated 
            // with the directive and return the appropriate response.
            switch ($directive)
            { 
                case static::ADDRESS_WHITELISTED:
                    $isListed = $this->repositories->isWhitelistedAddress($address);
                    break;

                case static::ADDRESS_BLACKLISTED:
                    $isListed = $this->repositories->isBlacklistedAddress($address);
                    break;

                default:
                    throw new InvalidArgumentException(
                        sprintf('The directive specified has no handler. [%s]', $directive)
                    );
            }

            if (true === $isListed) {
                return $this->respond($address, $action, $directive);
            }
        }

        // The request was not handled by any of the configured directives, 
        // so we need to return the default response, this can be setup within 
        // the packages configuration.
        return $this->respond($address, $this->config['default']);
    }

    protected function respond($address, $action, $directive = null)
    {
        if (static::ACTION_ALLOW === $action) {
            return true;
        } 

        elseif(static::ACTION_DENY === $action) {
            return false;
        }

        // The action provided was neither allow or deny, confusing :/
        else {
            throw new InvalidArgumentException(
                sprintf('The action specified is invalid. [%s]', $action)
            );
        }
    }

    protected function resolveAddressFromRequest(Request $request)
    {
        if (!empty($request->server->get('HTTP_CLIENT_IP'))) {
            return $request->server->get('HTTP_CLIENT_IP');
        }

        if (!empty($request->server->get('HTTP_X_FORWARDED_FOR'))) {
            return $request->server->get('HTTP_X_FORWARDED_FOR');
        }

        return $request->server->get('REMOTE_ADDR');
    }

    protected function event()
    {
        if ($this->dispatcher) {
            $this->dispatcher->dispatch(...func_get_args());
        }
    }

    public function setEventDispatcher(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function getEventDispatcher()
    {
        return $this->dispatcher;
    }
}