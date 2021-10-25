<?php

namespace Timetorock\LaravelRocketChat\Client;

use Exception;
use Httpful\Exception\ConnectionErrorException;
use Timetorock\LaravelRocketChat\Exceptions\TeamActionException;
use Timetorock\LaravelRocketChat\Models\Team;

/**
 * Operations with teams only.
 * See ChannelClient for public channels.
 * See GroupClient for private channels.
 * Class TeamClient
 */
class TeamClient extends Client
{
    const API_PATH_TEAM_CREATE           = 'teams.create';
    const API_PATH_TEAM_ADD_ROOMS        = 'teams.addRooms';
    const API_PATH_TEAM_REMOVE_ROOM      = 'teams.removeRoom';
    const API_PATH_TEAM_UPDATE_ROOM      = 'teams.updateRoom';
    const API_PATH_TEAM_LIST_ROOMS       = 'teams.listRooms';
    const API_PATH_TEAM_GET_MEMBERS      = 'teams.members';
    const API_PATH_TEAM_ADD_MEMBERS      = 'teams.addMembers';
    const API_PATH_TEAM_REMOVE_MEMBER    = 'teams.removeMember';
    const API_PATH_TEAM_GET_INFO         = 'teams.info';
    const API_PATH_TEAM_DELETE           = 'teams.delete';
    const API_PATH_TEAM_UPDATE           = 'teams.update';

    /**
     * Creates a new team, optionally including specified users and rooms.
     *
     * @param Team $team
     *
     * @return Team
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function create(Team $team)
    {
        $response = $this->request()->post($this->apiUrl(self::API_PATH_TEAM_CREATE))
            ->body($team->getFillableData())
            ->send();

        $teamData = $this->handleResponse($response, new TeamActionException(), ['team']);

        return new Team([
            'id'      => $teamData->_id,
            'name'    => $teamData->name,
            'type'    => $teamData->type,
        ]);
    }

    /**
     * Add rooms to the team.
     *
     * @param string $teamID
     * @param array $rooms
     *
     * @return string
     * @throws ConnectionErrorException
     * @throws TeamActionException
     * @throws Exception
     */
    public function addRooms($teamID, $rooms = [])
    {
        if (!$teamID) {
            throw new TeamActionException("Team ID not specified.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_TEAM_ADD_ROOMS))
            ->body(['teamId' => $teamID, 'rooms' => $rooms])
            ->send();

        return $this->handleResponse($response, new TeamActionException());
    }

    /**
     * Remove room from the team.
     *
     * @param string $teamID
     * @param string $roomID
     *
     * @return string
     * @throws ConnectionErrorException
     * @throws TeamActionException
     * @throws Exception
     */
    public function addRoom($teamID, $roomID)
    {
        if (!$teamID) {
            throw new TeamActionException("Team ID not specified.");
        }

        if (!$roomID) {
            throw new TeamActionException("Room ID not specified.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_TEAM_REMOVE_ROOM))
            ->body(['teamId' => $teamID, 'roomId' => $roomID])
            ->send();

        return $this->handleResponse($response, new TeamActionException());
    }

    /**
     * Update room from the team.
     *
     * @param string $roomID
     * @param boolean $default
     *
     * @return string
     * @throws ConnectionErrorException
     * @throws TeamActionException
     * @throws Exception
     */
    public function updateRoom($roomID, $default = true)
    {
        if (!$roomID) {
            throw new TeamActionException("Room ID not specified.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_TEAM_UPDATE_ROOM))
            ->body(['roomId' => $roomID, 'isDefault' => $default])
            ->send();

        return $this->handleResponse($response, new TeamActionException());
    }

    /**
     * List rooms from the team.
     *
     * @param string $teamID
     *
     * @return string
     * @throws ConnectionErrorException
     * @throws TeamActionException
     * @throws Exception
     */
    public function listRooms($teamID)
    {
        if (!$teamID) {
            throw new TeamActionException("Team ID not specified.");
        }

        $response = $this->request()->get(
            $this->apiUrl(
                self::API_PATH_TEAM_LIST_ROOMS, 
                ['teamId' => $teamID])
            )->send();

        return $this->handleResponse($response, new TeamActionException());
    }

    /**
     * List members from the team.
     *
     * @param string $teamID
     *
     * @return string
     * @throws ConnectionErrorException
     * @throws TeamActionException
     * @throws Exception
     */
    public function listMembers($teamID)
    {
        if (!$teamID) {
            throw new TeamActionException("Team ID not specified.");
        }

        $response = $this->request()->get(
            $this->apiUrl(
                self::API_PATH_TEAM_LIST_MEMBERS, 
                ['teamId' => $teamID])
            )->send();

        return $this->handleResponse($response, new TeamActionException());
    }

    /**
     * Add members to the team.
     *
     * @param string $teamID
     * @param array $members
     *
     * @return string
     * @throws ConnectionErrorException
     * @throws TeamActionException
     * @throws Exception
     */
    public function addMembers($teamID, $members = [])
    {
        if (!$teamID) {
            throw new TeamActionException("Team ID not specified.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_TEAM_ADD_MEMBERS))
            ->body(['teamId' => $teamID, 'members' => $members])
            ->send();

        return $this->handleResponse($response, new TeamActionException());
    }

    /**
     * Remove member from the team.
     *
     * @param string $teamID
     * @param string $memberID
     * @param array $rooms
     *
     * @return string
     * @throws ConnectionErrorException
     * @throws TeamActionException
     * @throws Exception
     */
    public function removeMembers($teamID, $memberID, $rooms = [])
    {
        if (!$teamID) {
            throw new TeamActionException("Team ID not specified.");
        }

        if (!$memberID) {
            throw new TeamActionException("Member ID not specified.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_TEAM_REMOVE_MEMBER))
            ->body(['teamId' => $teamID, 'userId' => $memberID, 'rooms' => $rooms])
            ->send();

        return $this->handleResponse($response, new TeamActionException());
    }

    /**
     * Retrieves the information about the team.
     *
     * @param string $teamID
     * @param string $paramType
     *
     * @return Team
     * @throws ConnectionErrorException
     * @throws TeamActionException
     * @throws Exception
     */
    public function info($teamID, $paramType = "teamId")
    {
        if (!$teamID) {
            throw new TeamActionException("Team ID not specified.");
        }

        if (!in_array($paramType, ["teamId", "teamName"])) {
            throw new TeamActionException("Bad method parameter value.");
        }

        $response = $this->request()->get(
            $this->apiUrl(self::API_PATH_TEAM_GET_INFO, [$paramType => $teamID])
        )->send();

        $teamData = $this->handleResponse($response, new TeamActionException(), ['teamInfo']);

        return new Team([
            'id'      => $teamData->_id,
            'name'    => $teamData->name,
            'type'    => $teamData->type,
        ]);
    }

    /**
     * Deletes the team.
     *
     * @param string $teamID
     * @param array $rooms
     *
     * @return $this
     * @throws ConnectionErrorException
     * @throws TeamActionException
     * @throws Exception
     */
    public function delete($teamID, $rooms)
    {
        if (!$teamID) {
            throw new TeamActionException("Team ID not specified.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_TEAM_DELETE))
            ->body(['teamId' => $teamID, 'roomsToRemove' => $rooms])
            ->send();

        return $this->handleResponse($response, new TeamActionException());
    }

    /**
     * Updates the team.
     *
     * @param string $teamID
     * @param array $data
     *
     * @return string
     * @throws ConnectionErrorException
     * @throws TeamActionException
     * @throws Exception
     */
    public function update($teamID, $data)
    {
        if (!$teamID) {
            throw new TeamActionException("Team ID not specified.");
        }

        if (!$data) {
            throw new TeamActionException("Data not specified.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_TEAM_UPDATE))
            ->body(['teamId' => $teamID, 'data' => $data])
            ->send();

        return $this->handleResponse($response, new TeamActionException());
    }
}
