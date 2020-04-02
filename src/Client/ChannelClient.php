<?php

namespace Timetorock\LaravelRocketChat\Client;

use Exception;
use Httpful\Exception\ConnectionErrorException;
use Timetorock\LaravelRocketChat\Models\Room;
use Timetorock\LaravelRocketChat\Exceptions\ChannelActionException;

/**
 * Operations with public channels only.
 * See ChannelClient for public channels.
 * Class ChannelClient
 */
class ChannelClient extends Client
{

    const CHANNEL = 'channel';
    const CHANNELS = 'channels';

    const API_PATH_CHANNEL_CREATE           = 'channels.create';
    const API_PATH_CHANNEL_ADD_ALL          = 'channels.addAll';
    const API_PATH_CHANNEL_ADD_MODERATOR    = 'channels.addModerator';
    const API_PATH_CHANNEL_ADD_OWNER        = 'channels.addOwner';
    const API_PATH_CHANNEL_ARCHIVE          = 'channels.archive';
    const API_PATH_CHANNEL_CLOSE            = 'channels.close';
    const API_PATH_CHANNEL_GET_INTEGRATIONS = 'channels.getIntegrations';
    const API_PATH_CHANNEL_GET_HISTORY      = 'channels.history';
    const API_PATH_CHANNEL_GET_INFO         = 'channels.info';
    const API_PATH_CHANNEL_INVITE           = 'channels.invite';
    const API_PATH_CHANNEL_KICK             = 'channels.kick';
    const API_PATH_CHANNEL_LEAVE            = 'channels.leave';
    const API_PATH_CHANNEL_LIST_ALL         = 'channels.list';
    const API_PATH_CHANNEL_OPEN             = 'channels.open';
    const API_PATH_CHANNEL_REMOVE_MODERATOR = 'channels.removeModerator';
    const API_PATH_CHANNEL_REMOVE_OWNER     = 'channels.removeOwner';
    const API_PATH_CHANNEL_RENAME           = 'channels.rename';
    const API_PATH_CHANNEL_SET_DESCRIPTION  = 'channels.setDescription';
    const API_PATH_CHANNEL_SET_JOIN_CODE    = 'channels.setJoinCode';
    const API_PATH_CHANNEL_SET_PURPOSE      = 'channels.setPurpose';
    const API_PATH_CHANNEL_SET_READ_ONLY    = 'channels.setReadOnly';
    const API_PATH_CHANNEL_SET_TOPIC        = 'channels.setTopic';
    const API_PATH_CHANNEL_SET_TYPE         = 'channels.setType';
    const API_PATH_CHANNEL_UNARCHIVE        = 'channels.unarchive';

    /**
     * Creates a new public channel, optionally including specified users.
     * The channel creator is always included.
     *
     * @param Room $room
     *
     * @return Room
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function create(Room $room)
    {
        $response = $this->request()->post($this->apiUrl(self::API_PATH_CHANNEL_CREATE))
            ->body($room->getFillableData())
            ->send();

        $channelData = $this->handleResponse($response, new ChannelActionException(), [self::CHANNEL]);

        return new Room([
            'id'      => $channelData->_id,
            'name'    => $channelData->name,
            'members' => property_exists($channelData, 'usernames') ? $channelData->usernames : [],
            'type'    => $channelData->t,
        ]);
    }

    /**
     * Adds all of the users of the Rocket.Chat server to the channel.
     *
     * @param string $roomId
     * @param bool   $activeUsersOnly
     *
     * @return $this
     * @throws ConnectionErrorException*@throws Exception
     * @throws Exception
     */
    public function addAll($roomId, $activeUsersOnly = false)
    {
        $response = $this->request()->post($this->apiUrl(self::API_PATH_CHANNEL_ADD_ALL))
            ->body(['roomId' => $roomId, 'activeUsersOnly' => $activeUsersOnly])
            ->send();

        return $this->handleResponse($response, new ChannelActionException(), self::CHANNEL);
    }

    /**
     * Gives the role of moderator for a user in the current channel.
     *
     * @param string $roomId
     * @param string $userId
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function addModerator($roomId, $userId)
    {
        $response = $this->request()->post($this->apiUrl(self::API_PATH_CHANNEL_ADD_MODERATOR))
            ->body(['roomId' => $roomId, 'userId' => $userId])
            ->send();

        return $this->handleResponse($response, new ChannelActionException());
    }

    /**
     * Gives the role of owner for a user in the current channel.
     *
     * @param string $roomId
     * @param string $userId
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function addOwner($roomId, $userId)
    {
        $response = $this->request()->post($this->apiUrl(self::API_PATH_CHANNEL_ADD_OWNER))
            ->body(['roomId' => $roomId, 'userId' => $userId])
            ->send();

        return $this->handleResponse($response, new ChannelActionException());
    }

    /**
     * Archives a channel.
     *
     * @param string $channelID
     *
     * @return $this
     * @throws ConnectionErrorException
     * @throws ChannelActionException
     * @throws Exception
     */
    public function archive($channelID)
    {
        if (!$channelID) {
            throw new ChannelActionException("Room ID not specified.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_CHANNEL_ARCHIVE))
            ->body(['roomId' => $channelID])
            ->send();

        return $this->handleResponse($response, new ChannelActionException());
    }

    /**
     * Removes the channel from the user’s list of channels.
     *
     * @param string $channelID
     *
     * @return $this
     * @throws ConnectionErrorException
     * @throws ChannelActionException
     * @throws Exception
     */
    public function close($channelID)
    {
        if (!$channelID) {
            throw new ChannelActionException("Room ID not specified.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_CHANNEL_CLOSE))
            ->body(['roomId' => $channelID])
            ->send();

        return $this->handleResponse($response, new ChannelActionException());
    }

    /**
     * Retrieves the integrations which the channel has,
     * requires the permission 'manage-integrations'
     *
     * @param string $channelID
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws ChannelActionException
     * @throws Exception
     */
    public function getIntegrations($channelID)
    {
        if (!$channelID) {
            throw new ChannelActionException("Room ID not specified.");
        }

        $response = $this->request()
            ->get($this->apiUrl(
                self::API_PATH_CHANNEL_GET_INTEGRATIONS,
                ["roomId" => $channelID])
            )->send();

        return $this->handleResponse($response, new ChannelActionException(), ['integrations']);
    }

    /**
     * Retrieves the messages from a channel.
     *
     * @param string $channelID
     * @param array  $params
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws ChannelActionException
     * @throws Exception
     */
    public function history($channelID, $params = [])
    {
        if (!$channelID) {
            throw new ChannelActionException("Room ID not specified.");
        }

        $params['roomId'] = $channelID;
        $response = $this->request()->get($this->apiUrl(self::API_PATH_CHANNEL_GET_HISTORY, $params))->send();

        return $this->handleResponse($response, new ChannelActionException(), ['messages']);
    }

    /**
     * Retrieves the information about the channel.
     *
     * @param string $channelID
     * @param string $paramType
     *
     * @return Room
     * @throws ConnectionErrorException
     * @throws ChannelActionException
     * @throws Exception
     */
    public function info($channelID, $paramType = "roomId")
    {
        if (!$channelID) {
            throw new ChannelActionException("Room ID not specified.");
        }

        if (!in_array($paramType, ["roomId", "roomName"])) {
            throw new ChannelActionException("Bad method parameter value.");
        }

        $response = $this->request()->get(
            $this->apiUrl(self::API_PATH_CHANNEL_GET_INFO, [$paramType => $channelID])
        )->send();

        $channelData = $this->handleResponse($response, new ChannelActionException(), [self::CHANNEL]);

        return new Room([
            'id'      => $channelData->_id,
            'name'    => $channelData->name,
            'members' => property_exists($channelData, 'usernames') ? $channelData->usernames : [],
            'type'    => $channelData->t,
        ]);
    }

    /**
     * Adds a user to the channel.
     *
     * @param string $roomId
     * @param string $userId
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function invite($roomId, $userId)
    {
        $response = $this->request()->post($this->apiUrl(self::API_PATH_CHANNEL_INVITE))
            ->body(['roomId' => $roomId, 'userId' => $userId])
            ->send();

        return $this->handleResponse($response, new ChannelActionException(), [self::CHANNEL]);
    }

    /**
     * Removes a user from the channel.
     *
     * @param string $roomId
     * @param string $userId
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function kick($roomId, $userId)
    {
        $response = $this->request()->post($this->apiUrl(self::API_PATH_CHANNEL_KICK))
            ->body(['roomId' => $roomId, 'userId' => $userId])
            ->send();

        return $this->handleResponse($response, new ChannelActionException(), [self::CHANNEL]);
    }

    /**
     * Causes the callee to be removed from the channel.
     *
     * @param string $id
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws ChannelActionException
     * @throws Exception
     */
    public function leave($id)
    {
        if (!$id) {
            throw new ChannelActionException("Room ID not specified.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_CHANNEL_LEAVE))
            ->body(['roomId' => $id])
            ->send();

        return $this->handleResponse($response, new ChannelActionException(), [self::CHANNEL]);
    }

    /**
     * Lists all of the channels on the server.
     * @return mixed
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function listAll()
    {
        $response = $this->request()->get($this->apiUrl(self::API_PATH_CHANNEL_LIST_ALL))->send();

        return $this->handleResponse($response, new ChannelActionException(), [self::CHANNELS]);
    }

    /**
     * Adds the channel back to the user’s list of channels.
     *
     * @param string $id
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws ChannelActionException
     * @throws Exception
     */
    public function open($id)
    {
        if (!$id) {
            throw new ChannelActionException("Room ID not specified.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_CHANNEL_OPEN))
            ->body(['roomId' => $id])
            ->send();

        return $this->handleResponse($response, new ChannelActionException());
    }

    /**
     * Removes the role of moderator from a user in the current channel.
     *
     * @param string $roomId
     * @param string $userId
     *
     * @return $this
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function removeModerator($roomId, $userId)
    {
        $response = $this->request()->post($this->apiUrl(self::API_PATH_CHANNEL_REMOVE_MODERATOR))
            ->body(['roomId' => $roomId, 'userId' => $userId])
            ->send();

        return $this->handleResponse($response, new ChannelActionException());
    }

    /**
     * Removes the role of owner from a user in the current channel.
     *
     * @param string $roomId
     * @param string $userId
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function removeOwner($roomId, $userId)
    {
        $response = $this->request()->post($this->apiUrl(self::API_PATH_CHANNEL_REMOVE_OWNER))
            ->body(['roomId' => $roomId, 'userId' => $userId])
            ->send();

        return $this->handleResponse($response, new ChannelActionException());
    }

    /**
     * Changes the name of the channel.
     *
     * @param string $newName
     * @param string $id
     *
     * @return Room
     * @throws ConnectionErrorException
     * @throws ChannelActionException
     * @throws Exception
     */
    public function rename($newName, $id)
    {
        if (!$id) {
            throw new ChannelActionException("Room ID not specified.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_CHANNEL_RENAME))
            ->body(['roomId' => $id, 'name' => $newName])
            ->send();

        $channelData = $this->handleResponse($response, new ChannelActionException(), [self::CHANNEL]);

        return new Room([
            'id'      => $channelData->_id,
            'name'    => $channelData->name,
            'members' => property_exists($channelData, 'usernames') ? $channelData->usernames : [],
            'type'    => $channelData->t,
        ]);
    }

    /**
     * Sets a public channel’s description.
     *
     * @param string $description
     * @param string $id
     *
     * @return string
     * @throws ConnectionErrorException
     * @throws ChannelActionException
     * @throws Exception
     */
    public function setDescription($description, $id)
    {
        if (!$id) {
            throw new ChannelActionException("Room ID not specified.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_CHANNEL_SET_DESCRIPTION))
            ->body(['roomId' => $id, 'description' => $description])
            ->send();

        return $this->handleResponse($response, new ChannelActionException(), ['description']);
    }

    /**
     * Sets the code required to join the channel.
     *
     * @param string $joinCode
     * @param string $id
     *
     * @return Room
     * @throws ConnectionErrorException
     * @throws ChannelActionException
     * @throws Exception
     */
    public function setJoinCode($joinCode, $id)
    {
        if (!$id) {
            throw new ChannelActionException("Room ID not specified.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_CHANNEL_SET_JOIN_CODE))
            ->body(['roomId' => $id, 'joinCode' => $joinCode])
            ->send();

        $channelData = $this->handleResponse($response, new ChannelActionException(), [self::CHANNEL]);

        return new Room([
            'id'      => $channelData->_id,
            'name'    => $channelData->name,
            'members' => property_exists($channelData, 'usernames') ? $channelData->usernames : [],
            'type'    => $channelData->t,
        ]);
    }

    /**
     * Sets the description for the public channel (the same as channels.setDescription, obsolete).
     *
     * @param string $channelID
     * @param string $purpose
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws ChannelActionException
     * @throws Exception
     */
    public function setPurpose($channelID, $purpose)
    {
        if (!$channelID) {
            throw new ChannelActionException("Room ID not specified.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_CHANNEL_SET_PURPOSE))
            ->body(['roomId' => $channelID, 'purpose' => $purpose])
            ->send();

        return $this->handleResponse($response, new ChannelActionException(), ['purpose']);
    }

    /**
     * Sets whether the channel is read only or not.
     *
     * @param string $channelID
     * @param        $readOnly
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws ChannelActionException
     * @throws Exception
     */
    public function setReadOnly($channelID, $readOnly = true)
    {
        if (!$channelID) {
            throw new ChannelActionException("Room ID not specified.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_CHANNEL_SET_READ_ONLY))
            ->body(['roomId' => $channelID, 'readOnly' => $readOnly])
            ->send();

        $channelData = $this->handleResponse($response, new ChannelActionException(), [self::CHANNEL]);

        return new Room([
            'id'      => $channelData->_id,
            'name'    => $channelData->name,
            'members' => property_exists($channelData, 'usernames') ? $channelData->usernames : [],
            'type'    => $channelData->t,
        ]);
    }


    /**
     * Sets the topic for the channel.
     *
     * @param string $channelID
     * @param string $topic
     *
     * @return string
     * @throws ConnectionErrorException
     * @throws ChannelActionException
     * @throws Exception
     */
    public function setTopic($channelID, $topic)
    {
        if (!$channelID) {
            throw new ChannelActionException("Room ID not specified.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_CHANNEL_SET_TOPIC))
            ->body(['roomId' => $channelID, 'topic' => $topic])
            ->send();

        return $this->handleResponse($response, new ChannelActionException(), ['topic']);
    }

    /**
     * Sets the type of room this channel should be.
     *
     * @param string $channelID
     * @param string $type ["c", "p"], chat or public.
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws ChannelActionException
     * @throws Exception
     */
    public function setType($channelID, $type)
    {
        if (!$channelID) {
            throw new ChannelActionException("Room ID not specified.");
        }

        if (!in_array($type, ["c", "p"], true)) {
            throw new ChannelActionException("Bad method parameter value.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_CHANNEL_SET_TYPE))
            ->body(['roomId' => $channelID, 'type' => $type])
            ->send();

        $channelData = $this->handleResponse($response, new ChannelActionException(), [self::CHANNEL]);

        return new Room([
            'id'      => $channelData->_id,
            'name'    => $channelData->name,
            'members' => property_exists($channelData, 'usernames') ? $channelData->usernames : [],
            'type'    => $channelData->t,
        ]);
    }

    /**
     * Unarchives a public channel.
     *
     * @param string $channelID
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws ChannelActionException
     * @throws Exception
     */
    public function unarchive($channelID)
    {
        if (!$channelID) {
            throw new ChannelActionException("Room ID not specified.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_CHANNEL_UNARCHIVE))
            ->body(['roomId' => $channelID])
            ->send();

        return $this->handleResponse($response, new ChannelActionException());
    }
}
