<?php

namespace Timetorock\LaravelRocketChat\Client\Responses\UserClient\UserInfo;

class UserInfoRooms
{
    protected array $rooms = [];

    public function __construct(array $rooms)
    {
        foreach ($rooms as $room) {
            $this->rooms[ $room->_id ] = new UserInfoRoom(
                (string) $room->_id,
                (string) $room->rid,
                (string) $room->name,
                (string) $room->t,
                (int) $room->unread,
                (array) $room->roles
            );
        }
    }

    public function setRoom(UserInfoRoom $room)
    {
        $this->rooms[ $room->getRoomID() ] = $room;
    }

    public function getRoom(string $roomID): UserInfoRoom
    {
        return $this->rooms[ $roomID ];
    }

    public function getRooms(): array
    {
        return $this->rooms;
    }
}