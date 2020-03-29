<?php

namespace Timetorock\LaravelRocketChat\Helpers;

use Httpful\Request;

class RocketChatRequest
{
    /**
     * @var Request
     */
    private static $request;

    /**
     * @return Request
     */
    public static function create()
    {
        self::$request = Request::init()->sendsJson()->expectsJson();
        Request::ini(self::$request);
        return self::$request;
    }

    /**
     * @return Request
     */
    public static function singleton()
    {
        if (self::$request) {
            return self::$request;
        }
        return self::create();
    }

    /**
     * Rewrite existing headers
     * @param $headers
     */
    public static function setHeaders($headers)
    {
        Request::ini(self::$request->addHeaders($headers));
    }
}