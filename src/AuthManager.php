<?php

namespace BoxedCode\Laravel\Auth\Ip;

use BoxedCode\Laravel\Auth\Ip\Contracts\AuthManager as ManagerContract;
use BoxedCode\Laravel\Auth\Ip\Contracts\Repository;
use BoxedCode\Laravel\Auth\Ip\Contracts\RepositoryManager;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use InvalidArgumentException;

class AuthManager implements ManagerContract
{
    /**
     * The repository manager instance.
     * 
     * @var \BoxedCode\Laravel\Auth\Ip\Contracts\RepositoryManager
     */
    protected $repository;

    /**
     * The configuration.
     * 
     * @var array
     */
    protected $config;

    /**
     * The event dispatcher instance.
     * 
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $dispatcher;

    /**
     * Create a new authorization manager instance.
     * 
     * @param \BoxedCode\Laravel\Auth\Ip\Contracts\RepositoryManager $repository
     * @param array $config
     */
    public function __construct(RepositoryManager $repository, array $config)
    {
        $this->repository = $repository;

        $this->config = $config;
    }

    /**
     * Determine whether a request is authorized and fire the authorization 
     * events as necessary.
     * 
     * @param  \Illuminate\Http\Request    $request
     * @param  array|null $directives
     * @return bool
     */
    public function authorize(Request $request, array $directives = null)
    {
        if ($this->validate($request, $directives)) {
            $this->event(new Events\Authorized($request));
            return true;
        }

        $this->event(new Events\Denied($request));
        return false;
    }

    /**
     * Determine whether a request is authorized.
     * 
     * @param  \Illuminate\Http\Request    $request
     * @param  array|null $directives
     * @return bool
     */
    public function validate(Request $request, array $directives = null)
    {
        $address = $this->resolveAddressFromRequest($request);

        $directives = $directives ?? $this->config['directives'];

        foreach ($directives as $list => $action) {
            // If the given address is listed within the list specified 
            // by the directive with check the action type associated 
            // with the directive and return the appropriate response.
            $isListed = $this->repository->exists(
                $address, 
                $list, 
                Repository::TYPE_ADDRESS
            );

            if (true === $isListed) {
                return $this->respond($address, $action, $list);
            }
        }

        // The request was not handled by any of the configured directives, 
        // so we need to return the default response, this can be setup within 
        // the packages configuration.
        return $this->respond($address, $this->config['default_action']);
    }

    /**
     * Transform a string of directive definitions into array format.
     * 
     * e.g. 'whitelist:allow;blacklist:deny' ==>
     *      ['whitelist' => 'allow', 'blacklist' => 'deny']
     * 
     * @param  string $directives
     * @return array
     */
    public function getDirectivesFromString(string $directives)
    {
        $stringDirectives = explode(';', $directives);

        $directives = [];

        foreach ($stringDirectives as $directive) {
            [$list, $action] = explode(':', $directive);
            $directives[$list] = $action; 
        }

        return $directives;
    }

    /**
     * Get the repository manager instance.
     * 
     * @return \BoxedCode\Laravel\Auth\Ip\Contracts\RepositoryManager
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Set the event dispatcher instance.
     * 
     * @param \Illuminate\Contracts\Events\Dispatcher $dispatcher
     */
    public function setEventDispatcher(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;

        return $this;
    }

    /**
     * Get the event dispatcher.
     * 
     * @return \Illuminate\Contracts\Events\Dispatcher|null
     */
    public function getEventDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * Fire an event if the dispatcher is set.
     * 
     * @return void
     */
    protected function event()
    {
        if ($this->dispatcher) {
            $this->dispatcher->dispatch(...func_get_args());
        }
    }

    /**
     * Respond to a directive match.
     * 
     * @param  string $address 
     * @param  string $action 
     * @param  string $list   
     * @return bool
     * @throws InvalidArgumentException
     */
    protected function respond($address, $action, $list = null)
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

    /**
     * Resolve an IP address from the current request.
     * 
     * @param  \Illuminate\Http\Request $request
     * @return string
     */
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
}