<?php

namespace Tests\Integration\Support;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthenticatesUsers;

    public function home(Request $request)
    {
        return 'Hello '.$request->user()->name.'!';
    }
}
