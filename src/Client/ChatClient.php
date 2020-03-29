<?php

namespace Timetorock\LaravelRocketChat\Client;

use Exception;
use Httpful\Exception\ConnectionErrorException;
use Timetorock\LaravelRocketChat\Exceptions\ChatActionException;

/**
 * Operations in existed chat.
 * Class ChatClient
 */
class ChatClient extends Client
{
    const API_PATH_CHAT_MESSAGE_POST   = 'chat.postMessage';
    const API_PATH_CHAT_MESSAGE_UPDATE = 'chat.update';
    const API_PATH_CHAT_MESSAGE_DELETE = 'chat.delete';

    /**
     * @param string $id
     * @param string $paramType
     * @param array  $params
     *
     * @return mixed
     * @throws ChatActionException
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function postMessage($id, $paramType = "roomId", $params = [])
    {
        if (!in_array($paramType, ["roomId", "channel"])) {
            throw new ChatActionException("Bad method parameter value.");
        }

        $postData = [];
        foreach ($params as $field => $value) {
            $postData[ $field ] = $value;
        }
        $postData[ $paramType ] = $id;

        $response = $this->request()->post($this->apiUrl(self::API_PATH_CHAT_MESSAGE_POST))
            ->body($postData)
            ->send();

        return $this->handleResponse($response, new ChatActionException());
    }

    /**
     * @param string $roomId
     * @param string $msgId
     * @param string $text
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function update($roomId, $msgId, $text)
    {
        $response = $this->request()->post($this->apiUrl(self::API_PATH_CHAT_MESSAGE_UPDATE))
            ->body([
                "roomId" => $roomId,
                "msgId"  => $msgId,
                "text"   => $text,
            ])->send();

        return $this->handleResponse($response, new ChatActionException(), ['message']);
    }

    /**
     * @param string $roomId
     * @param string $msgId
     * @param bool   $asUser
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function delete($roomId, $msgId, $asUser = false)
    {
        $response = $this->request()->post($this->apiUrl(self::API_PATH_CHAT_MESSAGE_DELETE))
            ->body([
                "roomId" => $roomId,
                "msgId"  => $msgId,
                "asUser" => $asUser,
            ])->send();

        return $this->handleResponse($response, new ChatActionException());
    }
}