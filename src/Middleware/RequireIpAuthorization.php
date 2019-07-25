<?php

namespace BoxedCode\Laravel\Auth\Ip\Middleware;

use BoxedCode\Laravel\Auth\Ip\Contracts\AuthManager;
use Closure;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class RequireIpAuthorization
{
    protected $manager;

    public function __construct(AuthManager $manager)
    {
        $this->manager = $manager;
    }

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
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$this->shouldAuthorize($request)) {
            if ($response = $this->redirect($request)) {
                return $response;
            }

            throw new AccessDeniedHttpException;
        }

        return $next($request);
    }

    protected function shouldAuthorize($request)
    {
        return $this->inExceptArray($request) || 
            $this->manager->authorize($request);
    }

    protected function redirect($request)
    {
        //
    }

    /**
     * Determine if the request has a URI that should pass through CSRF verification.
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
