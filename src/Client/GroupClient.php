<?php

namespace Timetorock\LaravelRocketChat\Client;

use Exception;
use Httpful\Exception\ConnectionErrorException;
use Timetorock\LaravelRocketChat\Exceptions\GroupActionException;
use Timetorock\LaravelRocketChat\Models\Room;

/**
 * Operations with private channels only.
 * See ChannelClient for public channels.
 * Class GroupClient
 */
class GroupClient extends Client
{
    const API_PATH_GROUP_CREATE           = 'groups.create';
    const API_PATH_GROUP_ADD_ALL          = 'groups.addAll';
    const API_PATH_GROUP_ADD_MODERATOR    = 'groups.addModerator';
    const API_PATH_GROUP_ADD_OWNER        = 'groups.addOwner';
    const API_PATH_GROUP_ARCHIVE          = 'groups.archive';
    const API_PATH_GROUP_CLOSE            = 'groups.close';
    const API_PATH_GROUP_GET_COUNTERS     = 'groups.counters';
    const API_PATH_GROUP_GET_INTEGRATIONS = 'groups.getIntegrations';
    const API_PATH_GROUP_GET_HISTORY      = 'groups.history';
    const API_PATH_GROUP_GET_INFO         = 'groups.info';
    const API_PATH_GROUP_DELETE           = 'groups.delete';
    const API_PATH_GROUP_INVITE           = 'groups.invite';
    const API_PATH_GROUP_KICK             = 'groups.kick';
    const API_PATH_GROUP_LEAVE            = 'groups.leave';
    const API_PATH_GROUP_LIST_ALL         = 'groups.list';
    const API_PATH_GROUP_OPEN             = 'groups.open';
    const API_PATH_GROUP_REMOVE_MODERATOR = 'groups.removeModerator';
    const API_PATH_GROUP_REMOVE_OWNER     = 'groups.removeOwner';
    const API_PATH_GROUP_RENAME           = 'groups.rename';
    const API_PATH_GROUP_SET_DESCRIPTION  = 'groups.setDescription';
    const API_PATH_GROUP_SET_JOIN_CODE    = 'groups.setJoinCode';
    const API_PATH_GROUP_SET_PURPOSE      = 'groups.setPurpose';
    const API_PATH_GROUP_SET_READ_ONLY    = 'groups.setReadOnly';
    const API_PATH_GROUP_SET_TOPIC        = 'groups.setTopic';
    const API_PATH_GROUP_SET_TYPE         = 'groups.setType';
    const API_PATH_GROUP_UNARCHIVE        = 'groups.unarchive';

    /**
     * Creates a new private group, optionally including specified users.
     * The group creator is always included.
     *
     * @param Room $group
     *
     * @return Room
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function create(Room $group)
    {
        $response = $this->request()->post($this->apiUrl(self::API_PATH_GROUP_CREATE))
            ->body($group->getFillableData())
            ->send();

        $groupData = $this->handleResponse($response, new GroupActionException(), ['group']);

        return new Room([
            'id'      => $groupData->_id,
            'name'    => $groupData->name,
            'members' => property_exists($groupData, 'usernames') ? $groupData->usernames : [],
            'type'    => $groupData->t,
        ]);
    }

    /**
     * Adds all of the users of the Rocket.Chat server to the group.
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
        $response = $this->request()->post($this->apiUrl(self::API_PATH_GROUP_ADD_ALL))
            ->body(['roomId' => $roomId, 'activeUsersOnly' => $activeUsersOnly])
            ->send();

        return $this->handleResponse($response, new GroupActionException());
    }

    /**
     * Gives the role of moderator for a user in the current group.
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
        $response = $this->request()->post($this->apiUrl(self::API_PATH_GROUP_ADD_MODERATOR))
            ->body(['roomId' => $roomId, 'userId' => $userId])
            ->send();

        return $this->handleResponse($response, new GroupActionException());
    }

    /**
     * Gives the role of owner for a user in the current group.
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
        $response = $this->request()->post($this->apiUrl(self::API_PATH_GROUP_ADD_OWNER))
            ->body(['roomId' => $roomId, 'userId' => $userId])
            ->send();

        return $this->handleResponse($response, new GroupActionException());
    }

    /**
     * Archives a group.
     *
     * @param string $groupID
     *
     * @return $this
     * @throws ConnectionErrorException
     * @throws GroupActionException
     * @throws Exception
     */
    public function archive($groupID)
    {
        if (!$groupID) {
            throw new GroupActionException("Room ID not specified.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_GROUP_ARCHIVE))
            ->body(['roomId' => $groupID])
            ->send();

        return $this->handleResponse($response, new GroupActionException());
    }

    /**
     * Removes the group from the user’s list of groups.
     *
     * @param string $groupID
     *
     * @return $this
     * @throws ConnectionErrorException
     * @throws GroupActionException
     * @throws Exception
     */
    public function close($groupID)
    {
        if (!$groupID) {
            throw new GroupActionException("Room ID not specified.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_GROUP_CLOSE))
            ->body(['roomId' => $groupID])
            ->send();

        return $this->handleResponse($response, new GroupActionException());
    }

    /**
     * @param string $roomID
     * @param string $roomName
     * @param string $userID
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws GroupActionException
     * @throws Exception
     */
    public function counters($roomID, $roomName = '', $userID = '')
    {
        $queryParams = [];

        if (!empty($roomID)) {
            $queryParams['roomId'] = $roomID;
        }

        if (!empty($roomName)) {
            $queryParams['roomName'] = $roomName;
        }

        if (!empty($userID)) {
            $queryParams['userId'] = $userID;
        }

        if (empty($queryParams['roomId']) && empty($queryParams['roomName'])) {
            throw new GroupActionException("Room ID or RoomName not specified.");
        }

        $response = $this->request()->get(
                $this->apiUrl(self::API_PATH_GROUP_GET_COUNTERS, $queryParams)
            )->send();

        return $this->handleResponse($response, new GroupActionException());
    }

    /**
     * Retrieves the integrations which the group has,
     * requires the permission 'manage-integrations'
     *
     * @param string $groupID
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws GroupActionException
     * @throws Exception
     */
    public function getIntegrations($groupID)
    {
        if (!$groupID) {
            throw new GroupActionException("Room ID not specified.");
        }

        $response = $this->request()
            ->get($this->apiUrl(
                self::API_PATH_GROUP_GET_INTEGRATIONS,
                ["roomId" => $groupID])
            )->send();

        return $this->handleResponse($response, new GroupActionException(), ['integrations']);
    }

    /**
     * Retrieves the messages from a group.
     *
     * @param string $groupID
     * @param array  $params
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws GroupActionException
     * @throws Exception
     */
    public function history($groupID, $params = [])
    {
        if (!$groupID) {
            throw new GroupActionException("Room ID not specified.");
        }

        $params['roomId'] = $groupID;
        $response = $this->request()->get($this->apiUrl(self::API_PATH_GROUP_GET_HISTORY, $params))->send();

        return $this->handleResponse($response, new GroupActionException(), ['messages']);
    }

    /**
     * Retrieves the information about the group.
     *
     * @param string $groupID
     * @param string $paramType
     *
     * @return Room
     * @throws ConnectionErrorException
     * @throws GroupActionException
     * @throws Exception
     */
    public function info($groupID, $paramType = "roomId")
    {
        if (!$groupID) {
            throw new GroupActionException("Room ID not specified.");
        }

        if (!in_array($paramType, ["roomId", "roomName"])) {
            throw new GroupActionException("Bad method parameter value.");
        }

        $response = $this->request()->get(
            $this->apiUrl(self::API_PATH_GROUP_GET_INFO, [$paramType => $groupID])
        )->send();

        $groupData = $this->handleResponse($response, new GroupActionException(), ['group']);

        return new Room([
            'id'      => $groupData->_id,
            'name'    => $groupData->name,
            'members' => property_exists($groupData, 'usernames') ? $groupData->usernames : [],
            'type'    => $groupData->t,
        ]);
    }

    /**
     * Deletes the group.
     *
     * @param string $groupID
     *
     * @return $this
     * @throws ConnectionErrorException
     * @throws GroupActionException
     * @throws Exception
     */
    public function delete($groupID)
    {
        if (!$groupID) {
            throw new GroupActionException("Room ID not specified.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_GROUP_DELETE))
            ->body(['roomId' => $groupID])
            ->send();

        return $this->handleResponse($response, new GroupActionException());
    }

    /**
     * Adds a user to the group.
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
        $response = $this->request()->post($this->apiUrl(self::API_PATH_GROUP_INVITE))
            ->body(['roomId' => $roomId, 'userId' => $userId])
            ->send();

        return $this->handleResponse($response, new GroupActionException(), ['group']);
    }

    /**
     * Removes a user from the group.
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
        $response = $this->request()->post($this->apiUrl(self::API_PATH_GROUP_KICK))
            ->body(['roomId' => $roomId, 'userId' => $userId])
            ->send();

        return $this->handleResponse($response, new GroupActionException(), ['group']);
    }

    /**
     * Causes the callee to be removed from the group.
     *
     * @param string $id
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws GroupActionException
     * @throws Exception
     */
    public function leave($id)
    {
        if (!$id) {
            throw new GroupActionException("Room ID not specified.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_GROUP_LEAVE))
            ->body(['roomId' => $id])
            ->send();

        return $this->handleResponse($response, new GroupActionException(), ['group']);
    }

    /**
     * Lists all of the groups on the server.
     * @return mixed
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function listAll()
    {
        $response = $this->request()->get($this->apiUrl(self::API_PATH_GROUP_LIST_ALL))->send();

        return $this->handleResponse($response, new GroupActionException(), ['groups']);
    }

    /**
     * Adds the group back to the user’s list of groups.
     *
     * @param string $id
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws GroupActionException
     * @throws Exception
     */
    public function open($id)
    {
        if (!$id) {
            throw new GroupActionException("Room ID not specified.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_GROUP_OPEN))
            ->body(['roomId' => $id])
            ->send();

        return $this->handleResponse($response, new GroupActionException());
    }

    /**
     * Removes the role of moderator from a user in the current group.
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
        $response = $this->request()->post($this->apiUrl(self::API_PATH_GROUP_REMOVE_MODERATOR))
            ->body(['roomId' => $roomId, 'userId' => $userId])
            ->send();

        return $this->handleResponse($response, new GroupActionException());
    }

    /**
     * Removes the role of owner from a user in the current group.
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
        $response = $this->request()->post($this->apiUrl(self::API_PATH_GROUP_REMOVE_OWNER))
            ->body(['roomId' => $roomId, 'userId' => $userId])
            ->send();

        return $this->handleResponse($response, new GroupActionException());
    }

    /**
     * Changes the name of the group.
     *
     * @param string $newName
     * @param string $id
     *
     * @return Room
     * @throws ConnectionErrorException
     * @throws GroupActionException
     * @throws Exception
     */
    public function rename($newName, $id)
    {
        if (!$id) {
            throw new GroupActionException("Room ID not specified.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_GROUP_RENAME))
            ->body(['roomId' => $id, 'name' => $newName])
            ->send();

        $groupData = $this->handleResponse($response, new GroupActionException(), ['group']);

        return new Room([
            'id'      => $groupData->_id,
            'name'    => $groupData->name,
            'members' => property_exists($groupData, 'usernames') ? $groupData->usernames : [],
            'type'    => $groupData->t,
        ]);
    }

    /**
     * Sets a private group’s description.
     *
     * @param string $description
     * @param string $id
     *
     * @return string
     * @throws ConnectionErrorException
     * @throws GroupActionException
     * @throws Exception
     */
    public function setDescription($description, $id)
    {
        if (!$id) {
            throw new GroupActionException("Room ID not specified.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_GROUP_SET_DESCRIPTION))
            ->body(['roomId' => $id, 'description' => $description])
            ->send();

        return $this->handleResponse($response, new GroupActionException(), ['description']);
    }

    /**
     * Sets the code required to join the group.
     *
     * @param string $joinCode
     * @param string $id
     *
     * @return Room
     * @throws ConnectionErrorException
     * @throws GroupActionException
     * @throws Exception
     */
    public function setJoinCode($joinCode, $id)
    {
        if (!$id) {
            throw new GroupActionException("Room ID not specified.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_GROUP_SET_JOIN_CODE))
            ->body(['roomId' => $id, 'joinCode' => $joinCode])
            ->send();

        $groupData = $this->handleResponse($response, new GroupActionException(), ['group']);

        return new Room([
            'id'      => $groupData->_id,
            'name'    => $groupData->name,
            'members' => property_exists($groupData, 'usernames') ? $groupData->usernames : [],
            'type'    => $groupData->t,
        ]);
    }

    /**
     * Sets the description for the private group (the same as groups.setDescription, obsolete).
     *
     * @param string $groupID
     * @param string $purpose
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws GroupActionException
     * @throws Exception
     */
    public function setPurpose($groupID, $purpose)
    {
        if (!$groupID) {
            throw new GroupActionException("Room ID not specified.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_GROUP_SET_PURPOSE))
            ->body(['roomId' => $groupID, 'purpose' => $purpose])
            ->send();

        return $this->handleResponse($response, new GroupActionException(), ['purpose']);
    }

    /**
     * Sets whether the group is read only or not.
     *
     * @param string $groupID
     * @param        $readOnly
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws GroupActionException
     * @throws Exception
     */
    public function setReadOnly($groupID, $readOnly = true)
    {
        if (!$groupID) {
            throw new GroupActionException("Room ID not specified.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_GROUP_SET_READ_ONLY))
            ->body(['roomId' => $groupID, 'readOnly' => $readOnly])
            ->send();

        $groupData = $this->handleResponse($response, new GroupActionException(), ['group']);

        return new Room([
            'id'      => $groupData->_id,
            'name'    => $groupData->name,
            'members' => property_exists($groupData, 'usernames') ? $groupData->usernames : [],
            'type'    => $groupData->t,
        ]);
    }


    /**
     * Sets the topic for the group.
     *
     * @param string $groupID
     * @param string $topic
     *
     * @return string
     * @throws ConnectionErrorException
     * @throws GroupActionException
     * @throws Exception
     */
    public function setTopic($groupID, $topic)
    {
        if (!$groupID) {
            throw new GroupActionException("Room ID not specified.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_GROUP_SET_TOPIC))
            ->body(['roomId' => $groupID, 'topic' => $topic])
            ->send();

        return $this->handleResponse($response, new GroupActionException(), ['topic']);
    }

    /**
     * Sets the type of room this group should be.
     *
     * @param string $groupID
     * @param string $type ["c", "p"], chat or private.
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws GroupActionException
     * @throws Exception
     */
    public function setType($groupID, $type)
    {
        if (!$groupID) {
            throw new GroupActionException("Room ID not specified.");
        }

        if (!in_array($type, ["c", "p"], true)) {
            throw new GroupActionException("Bad method parameter value.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_GROUP_SET_TYPE))
            ->body(['roomId' => $groupID, 'type' => $type])
            ->send();

        $groupData = $this->handleResponse($response, new GroupActionException(), ['group']);

        return new Room([
            'id'      => $groupData->_id,
            'name'    => $groupData->name,
            'members' => property_exists($groupData, 'usernames') ? $groupData->usernames : [],
            'type'    => $groupData->t,
        ]);
    }

    /**
     * Unarchives a private group.
     *
     * @param string $groupID
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws GroupActionException
     * @throws Exception
     */
    public function unarchive($groupID)
    {
        if (!$groupID) {
            throw new GroupActionException("Room ID not specified.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_GROUP_UNARCHIVE))
            ->body(['roomId' => $groupID])
            ->send();

        return $this->handleResponse($response, new GroupActionException());
    }
}
