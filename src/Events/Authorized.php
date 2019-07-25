<?php

namespace BoxedCode\Laravel\Auth\Ip\Events;

use Illuminate\Http\Request;

class Authorized
{
    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
