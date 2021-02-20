<?php

namespace BoxedCode\Laravel\Auth\Ip\Events;

use Illuminate\Http\Request;

class Authorized
{
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    public $request;

    /**
     * Create a new authorized event instance.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
