<?php

namespace BoxedCode\Laravel\Auth\Ip\Contracts;

interface AuthManager
{
    /**
     * Constant that represents the intention to authorize a request.
     */
    const ACTION_ALLOW = 'allow';

    /**
     * Constant that represents the intention to deny a request.
     */
    const ACTION_DENY = 'deny';

    /**
     * Determine whether a request is authorized and fire the authorization 
     * events as necessary.
     * 
     * @param  \Illuminate\Http\Request    $request
     * @param  array|null $directives
     * @return bool
     */
    public function authorize(Request $request, array $directives = null);

    /**
     * Determine whether a request is authorized.
     * 
     * @param  \Illuminate\Http\Request    $request
     * @param  array|null $directives
     * @return bool
     */
    public function validate(Request $request, array $directives = null);

    /**
     * Transform a string of directive definitions into array format.
     * 
     * e.g. 'whitelist:allow;blacklist:deny' ==>
     *      ['whitelist' => 'allow', 'blacklist' => 'deny']
     * 
     * @param  string $directives
     * @return array
     */
    public function getDirectivesFromString(string $directives);

    /**
     * Get the repository manager instance.
     * 
     * @return \BoxedCode\Laravel\Auth\Ip\Contracts\RepositoryManager
     */
    public function getRepository();

    /**
     * Set the event dispatcher instance.
     * 
     * @param \Illuminate\Contracts\Events\Dispatcher $dispatcher
     */
    public function setEventDispatcher(Dispatcher $dispatcher);

    /**
     * Get the event dispatcher.
     * 
     * @return \Illuminate\Contracts\Events\Dispatcher|null
     */
    public function getEventDispatcher();
}