<?php

namespace Timetorock\LaravelRocketChat\Facades;

use Illuminate\Support\Facades\Facade;

class RocketChatClient extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-rocket-chat-client';
    }
}