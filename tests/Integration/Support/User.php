<?php

namespace Tests\Integration\Support;

use Illuminate\Foundation\Auth\User as BaseUser;

class User extends BaseUser
{
    protected $table = 'users';

    protected $fillable = ['name', 'email', 'password'];
}
