<?php

namespace Timetorock\LaravelRocketChat\Client;

use Exception;
use Httpful\Exception\ConnectionErrorException;
use Timetorock\LaravelRocketChat\Exceptions\LivechatActionException;

class LivechatClient extends Client
{
    const API_PATH_LIVECHAT_DEPARTMENT             = 'livechat/department';
    const API_PATH_LIVECHAT_DEPARTMENT_ITEM        = 'livechat/department/%s';
    const API_PATH_LIVECHAT_SAVE_SMS_ON_ROCKETCHAT = 'livechat/sms-incoming/%s';
    const API_PATH_LIVECHAT_USERS_TYPE             = 'livechat/users/%s';
    const API_PATH_LIVECHAT_USER_INFO              = 'livechat/users/%s/%s';

    /**
     * Get a list of departments.
     * It supports the Offset, Count, and Sort Query Parameters.
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function departments()
    {
        $response = $this->request()->get($this->apiUrl(self::API_PATH_LIVECHAT_DEPARTMENT))->send();
        return $this->handleResponse($response, new LivechatActionException(), ['departments']);
    }

    /**
     * Register a new department.
     *
     * @param $postData
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function addDepartment($postData)
    {
        $response = $this->request()->post($this->apiUrl(self::API_PATH_LIVECHAT_DEPARTMENT))
            ->body($postData)->send();

        return $this->handleResponse($response, new LivechatActionException());
    }

    /**
     * Retrieve a Livechat department data.
     *
     * @param string $id
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function department($id)
    {
        $response = $this->request()->get($this->apiUrl(sprintf(self::API_PATH_LIVECHAT_DEPARTMENT_ITEM, $id)))->send();
        return $this->handleResponse($response, new LivechatActionException());
    }

    /**
     * Updates a Livechat department data.
     *
     * @param string $id
     * @param array  $postData
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function updateDepartment($id, array $postData)
    {
        $response = $this->request()->put($this->apiUrl(sprintf(self::API_PATH_LIVECHAT_DEPARTMENT_ITEM, $id)))
            ->body($postData)->send();

        return $this->handleResponse($response, new LivechatActionException());
    }

    /**
     * @param string $id
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function removeDepartment($id)
    {
        $response = $this->request()->delete(
            $this->apiUrl(sprintf(self::API_PATH_LIVECHAT_DEPARTMENT_ITEM, $id))
        )->send();
        return $this->handleResponse($response, new LivechatActionException(), ['success']);
    }

    /**
     * Save a SMS message on Rocket.Chat.
     *
     * @param $service
     * @param $postData
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function smsIncoming($service, $postData)
    {
        $response = $this->request()
            ->post(
                $this->apiUrl(sprintf(self::API_PATH_LIVECHAT_SAVE_SMS_ON_ROCKETCHAT, $service))
            )
            ->body($postData)->send();

        return $this->handleResponse($response, new LivechatActionException());
    }

    /**
     * Get a list of agents or managers.
     *
     * @param string $type
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function users($type = "agent")
    {
        $response = $this->request()
            ->get(
                $this->apiUrl(sprintf(self::API_PATH_LIVECHAT_USERS_TYPE, $type))
            )->send();

        return $this->handleResponse($response, new LivechatActionException(), ['users']);
    }

    /**
     * Register new agent or manager.
     *
     * @param string $type
     * @param array  $postData
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function addUser($type, $postData)
    {
        $response = $this->request()
            ->post($this->apiUrl(sprintf(self::API_PATH_LIVECHAT_USERS_TYPE, $type)))
            ->body($postData)->send();

        return $this->handleResponse($response, new LivechatActionException(), ['user']);
    }

    /**
     * Get info about an agent or manager.
     *
     * @param string $id
     * @param string $type
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function user($id, $type = "agent")
    {
        $response = $this->request()
            ->get($this->apiUrl(sprintf(self::API_PATH_LIVECHAT_USER_INFO, $type, $id)))
            ->send();

        return $this->handleResponse($response, new LivechatActionException(), ['user']);
    }

    /**
     * Removes an agent or manager.
     *
     * @param string $id
     * @param string $type
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function removeUser($id, $type = "agent")
    {
        $response = $this->request()
            ->delete(sprintf(self::API_PATH_LIVECHAT_USER_INFO, $type, $id))
            ->send();

        return $this->handleResponse($response, new LivechatActionException(), ['success']);
    }
}