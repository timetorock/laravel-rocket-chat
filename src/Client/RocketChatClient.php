<?php

namespace Timetorock\LaravelRocketChat\Client;

use Httpful\Exception\ConnectionErrorException;

class RocketChatClient extends Client
{

    const API_PATH_INFO = '/api/info';

    /**
     * A simple method, requires no authentication,
     * that returns information about the server version.
     * @return mixed
     * @throws ConnectionErrorException
     */
    public function version()
    {
        $response = $this->request()->get(self::API_PATH_INFO)->send();
        return $response->body->info->version;
    }

    /**
     *
     * A simple method, requires no authentication,
     * that returns information about the server including version information.
     * @return mixed
     * @throws ConnectionErrorException
     */
    public function info()
    {
        $response = $this->request()->get(self::API_PATH_INFO)->send();
        return $response->body->info;
    }
}
