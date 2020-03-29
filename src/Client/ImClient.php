<?php

namespace Timetorock\LaravelRocketChat\Client;

use Exception;
use Httpful\Exception\ConnectionErrorException;
use Timetorock\LaravelRocketChat\Exceptions\ImActionException;

class ImClient extends Client
{
    const API_PATH_IM_CLOSE           = 'im.close';
    const API_PATH_IM_HISTORY         = 'im.history';
    const API_PATH_IM_LIST            = 'im.list';
    const API_PATH_IM_LIST_EVERYONE   = 'im.list.everyone';
    const API_PATH_IM_MESSAGES_OTHERS = 'im.messages.others';
    const API_PATH_IM_OPEN            = 'im.open';
    const API_PATH_IM_SET_TOPIC       = 'channels.setTopic';

    /**
     * Removes the direct message from the user’s list of direct messages
     *
     * @param string $id
     *
     * @return mixed
     * @throws ImActionException
     * @throws ConnectionErrorException
     * @throws Exception
     */

    public function close($id)
    {
        if (!$id) {
            throw new ImActionException("Im ID not specified.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_IM_CLOSE))
            ->body(['roomId' => $id])
            ->send();

        return $this->handleResponse($response, new ImActionException());
    }

    /**
     * Retrieves the messages from a direct message.
     *
     * @param string $id
     * @param array  $params
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws ImActionException
     * @throws Exception
     */
    public function history($id, $params = [])
    {
        if (!$id) {
            throw new ImActionException("Im ID not specified.");
        }

        $params["roomId"] = $id;
        $response = $this->request()->get($this->apiUrl(self::API_PATH_IM_HISTORY, $params))->send();

        return $this->handleResponse($response, new ImActionException(), ['messages']);
    }

    /**
     * Lists all of the direct messages in the server, requires the permission 'view-room-administration' permission.
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function listEveryone()
    {
        $response = $this->request()->get($this->apiUrl(self::API_PATH_IM_LIST_EVERYONE))->send();

        return $this->handleResponse($response, new ImActionException(), ['ims']);
    }

    /**
     * Lists all of the direct messages the calling user has joined.
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function list()
    {
        $response = $this->request()->get($this->apiUrl(self::API_PATH_IM_LIST))->send();

        return $this->handleResponse($response, new ImActionException(), ['ims']);
    }

    /**
     * Retrieves the messages from any direct message in the server.
     * It supports the Offset, Count, and Sort Query Parameters along with Query and Fields Query Parameter.
     * For this method to work the Enable Direct Message History Endpoint setting must be true,
     * and the user calling this method must have the view-room-administration permission.
     *
     * @param string $groupID
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws ImActionException
     * @throws Exception
     */
    public function othersMessages($groupID)
    {
        if (!$groupID) {
            throw new ImActionException("Im ID not specified.");
        }

        $response = $this->request()
            ->get(
                $this->apiUrl(self::API_PATH_IM_MESSAGES_OTHERS, ["roomId" => $groupID])
            )->send();

        return $this->handleResponse($response, new ImActionException(), ['messages']);
    }

    /**
     * Adds the direct message back to the user’s list of direct messages.
     *
     * @param string $groupID
     *
     * @return $this
     * @throws ConnectionErrorException
     * @throws ImActionException
     * @throws Exception
     */
    public function open($groupID)
    {
        if (!$groupID) {
            throw new ImActionException("Im ID not specified.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_IM_OPEN))
            ->body(['roomId' => $groupID])
            ->send();

        return $this->handleResponse($response, new ImActionException());
    }

    /**
     * Sets the topic for the direct message.
     *
     * @param string $groupID
     * @param string $topic
     *
     * @return string
     * @throws ConnectionErrorException
     * @throws ImActionException
     * @throws Exception
     */
    public function setTopic($groupID, $topic)
    {
        if (!$groupID) {
            throw new ImActionException("Im ID not specified.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_IM_SET_TOPIC))
            ->body(['roomId' => $groupID, 'topic' => $topic])
            ->send();

        return $this->handleResponse($response, new ImActionException(), ['topic']);
    }
}