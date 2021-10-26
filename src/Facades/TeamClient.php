<?php

namespace Timetorock\LaravelRocketChat\Facades;

use Illuminate\Support\Facades\Facade;

class TeamClient extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'rc-team-client';
    }
}