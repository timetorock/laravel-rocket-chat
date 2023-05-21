<?php

namespace Timetorock\LaravelRocketChat\Client\Responses\UserClient\UserInfo;

class UserInfoRooms
{
    protected array $rooms = [];

    public function __construct(array $rooms)
    {
        foreach ($rooms as $room) {
            $this->rooms[ $room->_id ] = new UserInfoRoom(
                $room->_id ?? '',
                $room->rid ?? '',
                $room->name ?? '',
                $room->t ?? '',
                $room->unread ?? 0,
                $room->roles ?? []
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