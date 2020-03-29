<?php

namespace Timetorock\LaravelRocketChat\Facades;

use Illuminate\Support\Facades\Facade;

class ChatClient extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'rc-chat-client';
    }
}