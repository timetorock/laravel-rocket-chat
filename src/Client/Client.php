<?php

namespace Timetorock\LaravelRocketChat\Client;

use Exception;
use Httpful\Exception\ConnectionErrorException;
use Httpful\Request;
use stdClass;
use Timetorock\LaravelRocketChat\Exceptions\InvalidCredentialsException;
use Timetorock\LaravelRocketChat\Exceptions\UserActionException;
use Timetorock\LaravelRocketChat\Helpers\RocketChatRequest;
use Timetorock\LaravelRocketChat\Models\UserAuthToken;

class Client
{

    const ROCKET_CHAT_SUCCESS_RESPONSE = 'success';
    const ROCKET_CHAT_ERROR_RESPONSE   = 'error';

    /**
     * @var string
     */
    private $apiUrl;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var array
     */
    private $extraQuery = [];

    /**
     * Login admin by default, create request object
     * and use config API url.
     *
     * If you need another behaviour it can be easily configured
     *
     * @param bool   $adminLogin
     * @param string $apiUrl
     *
     * @throws Exception
     */
    function __construct($adminLogin = true, $apiUrl = '')
    {
        $this->apiUrl = $apiUrl ?: config("laravel-rocket-chat.instance") . config("laravel-rocket-chat.api_root");
        $this->request = RocketChatRequest::singleton();

        if (!$adminLogin) {
            return;
        }

        $this->adminLogin();
    }

    /**
     * Prepare API URL for request
     *
     * @param       $path
     * @param array $queryParams
     *
     * @return string
     */
    protected function apiUrl($path, $queryParams = [])
    {
        $path = rtrim($this->apiUrl, "/") . "/" . ltrim($path, "/");
        $query = array_merge($queryParams, $this->extraQuery);

        $url = count($query) ? $path . "?" . http_build_query($query) : $path;
        $this->extraQuery = [];
        return $url;
    }

    /**
     * @param array $headers
     */
    private function setRequestHeaders(array $headers)
    {
        RocketChatRequest::setHeaders($headers);
    }

    /**
     * Return all or requested headers from request
     * @param array $keys
     *
     * @return array
     */
    private function getRequestHeaders($keys = [])
    {
        if (empty($keys)) {
            return $this->request->headers;
        }

        $headers = [];

        foreach ($this->request->headers as $header) {
            if (!in_array($header, $keys)) {
                continue;
            }

            $headers[] = $header;
        }

        return $headers;
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @return UserAuthToken
     * @throws ConnectionErrorException
     * @throws InvalidCredentialsException
     * @throws Exception
     */
    public function authToken(string $username, string $password) {
        if (!$username || !$password) {
            throw new InvalidCredentialsException();
        }

        $response = $this->request()->post($this->apiUrl(UserClient::API_PATH_LOGIN))
            ->body([
                'user'     => $username,
                'password' => $password,
            ])
            ->send();

        $data = $this->handleResponse($response, new UserActionException(), ['data']);

        $this->setAuth($data->userId, $data->authToken);

        return new UserAuthToken($data->userId, $data->authToken);
    }

    /**
     * @throws ConnectionErrorException
     * @throws InvalidCredentialsException
     */
    public function adminLogin()
    {
        $adminUserID = config('laravel-rocket-chat.admin_id', '');
        $adminToken = config('laravel-rocket-chat.admin_token', '');

        if ($adminUserID && $adminToken) {
            $this->setAuth($adminUserID, $adminToken);
            return;
        }

        $adminUser = config('laravel-rocket-chat.admin_username');
        $adminPassword = config('laravel-rocket-chat.admin_password');

        $auth = $this->authToken($adminUser, $adminPassword);

        $this->setAuth($auth->getId(), $auth->getToken());
    }

    /**
     * Rewrite user credentials on behalf of which
     * we will make further requests.
     *
     * @param $userId
     * @param $authToken
     */
    public function setAuth($userId, $authToken)
    {
        $this->setRequestHeaders([
            'X-User-Id'    => $userId,
            'X-Auth-Token' => $authToken,
        ]);
    }

    /**
     * Get current authentication headers
     */
    public function getAuth()
    {
        $this->getRequestHeaders([
            'X-User-Id', 'X-Auth-Token'
        ]);
    }

    /**
     * @param           $response
     * @param Exception $exception
     * @param array     $fields
     *
     * @return mixed
     * @throws Exception
     */
    public function handleResponse($response, Exception $exception, $fields = [])
    {
        $fields = is_string($fields) ? [$fields] : $fields;
        try {
            if ($response->code == 200) {

                if (isset($response->body->success) && $response->body->success == true) {
                    return $this->data($response->body, $fields);
                } else if (isset($response->body->status) && $response->body->status == self::ROCKET_CHAT_SUCCESS_RESPONSE) {
                    return $this->data($response->body, $fields);
                } else if (isset($response->body->status) && $response->body->status == self::ROCKET_CHAT_ERROR_RESPONSE) {
                    $exception->setMessage($response->body->message);
                } else if (isset($response->body->success) && $response->body->success == false) {
                    $exception->setMessage($response->body->error);
                } else {
                    $exception->setMessage("something went wrong");
                }

            } else {
                if (isset($response->body->status) && $response->body->status == self::ROCKET_CHAT_ERROR_RESPONSE) {
                    $exception->setMessage($response->body->message);
                } else if (isset($response->body->success) && $response->body->success == false) {
                    $exception->setMessage($response->body->error);
                } else {
                    $exception->setMessage("something went wrong");
                }
            }

        } catch (Exception $ex) {
            $exception->setMessage("something went wrong");
        }
        throw $exception;
    }

    /**
     * @param $body
     * @param $fields
     *
     * @return mixed
     */
    private function data($body, $fields)
    {
        if (count($fields) == 0) {
            return $body;
        } else if (count($fields) == 1) {
            return isset($body->{$fields[0]}) ? $body->{$fields[0]} : $body;
        } else if (count($fields) == 2) {
            $stepOne = isset($body->{$fields[0]}) ? $body->{$fields[0]} : $body;
            $stepTwo = isset($stepOne->{$fields[1]}) ? $stepOne->{$fields[1]} : $stepOne;
            return $stepTwo;
        } else if (count($fields) == 3) {
            $stepOne = isset($body->{$fields[0]}) ? $body->{$fields[0]} : $body;
            $stepTwo = isset($stepOne->{$fields[1]}) ? $stepOne->{$fields[1]} : $stepOne;
            $stepThree = isset($stepTwo->{$fields[2]}) ? $stepTwo->{$fields[2]} : $stepTwo;
            return $stepThree;
        }
        return $body;
    }

    /**
     * To use the next three methods the rest api method need to support the Offset and Count Query Parameters.
     *
     * @param $value
     *
     * @return $this
     */
    public function skip($value)
    {
        $this->extraQuery["offset"] = $value;

        return $this;
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function take($value)
    {
        $this->extraQuery["count"] = $value;

        return $this;
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function sort($value)
    {
        $value = is_array($value) ? json_encode((object) $value) : $value;
        $this->extraQuery["sort"] = $value;

        return $this;
    }

    /**
     * @return Request
     */
    protected function request()
    {
        return $this->request;
    }
}
