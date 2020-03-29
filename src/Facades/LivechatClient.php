<?php

namespace Timetorock\LaravelRocketChat\Facades;

use Illuminate\Support\Facades\Facade;

class LivechatClient extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'rc-livechat-client';
    }
}