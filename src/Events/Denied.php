<?php

namespace BoxedCode\Laravel\Auth\Ip\Events;

use Illuminate\Http\Request;

class Denied
{
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    public $request;

    /**
     * Create a new denied event instance.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
