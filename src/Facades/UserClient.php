<?php

namespace Timetorock\LaravelRocketChat\Facades;

use Illuminate\Support\Facades\Facade;

class UserClient extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'rc-user-client';
    }
}