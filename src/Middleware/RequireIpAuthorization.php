<?php

namespace BoxedCode\Laravel\Auth\Ip\Middleware;

use BoxedCode\Laravel\Auth\Ip\Contracts\AuthManager;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Closure;

class RequireIpAuthorization
{
    /**
     * The authorization manager instance.
     * 
     * @var \BoxedCode\Laravel\Auth\Ip\Contracts\AuthManager
     */
    protected $manager;

    /**
     * The paths that should be excluded from 
     * two factor authentication.
     *
     * @var array
     */
    protected $except = [
        //
    ];

    /**
     * Create a new middleware instance.
     * 
     * @param \BoxedCode\Laravel\Auth\Ip\Contracts\AuthManager $manager
     */
    public function __construct(AuthManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  array $directives
     * @return mixed
     * @throws AccessDeniesHttpException 
     */
    public function handle($request, Closure $next, $directives = null)
    {
        if (!$this->shouldAuthorize($request, $directives)) {
            if ($response = $this->redirect($request)) {
                return $response;
            }

            throw new AccessDeniedHttpException;
        }

        return $next($request);
    }

    /**
     * Optionally handle the response instead of throwing
     * an AccessDeniedHttpException.
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    protected function redirect($request)
    {
        //
    }

    /**
     * Determine whether the request should be authorized or denied.
     * 
     * @param  \Illuminate\Http\Request $request    
     * @param  string|null $directives 
     * @return bool
     */
    protected function shouldAuthorize($request, $directives = null)
    {
        // Parse the directives from string to array.
        if (is_string($directives)) {
            $directives = $this->manager->getDirectivesFromString(
                $directives
            );
        }

        // Check the current route path is not within the 'except' 
        // array and then pass the request to the authentication 
        // manager for processing.
        return $this->inExceptArray($request) || 
            $this->manager->authorize($request, $directives);
    }

    /**
     * Determine if the request has a URI that should pass through IP authorization.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function inExceptArray($request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;
    }
}
