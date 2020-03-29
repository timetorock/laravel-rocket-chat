<?php

namespace Timetorock\LaravelRocketChat\Facades;

use Illuminate\Support\Facades\Facade;

class ChannelClient extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'rc-channel-client';
    }
}