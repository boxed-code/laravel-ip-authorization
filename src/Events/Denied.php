<?php

namespace BoxedCode\Laravel\Auth\Ip\Events;

use Illuminate\Http\Request;

class Denied
{
    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
