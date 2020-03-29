<?php

namespace Timetorock\LaravelRocketChat\Facades;

use Illuminate\Support\Facades\Facade;

class ImClient extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'rc-im-client';
    }
}