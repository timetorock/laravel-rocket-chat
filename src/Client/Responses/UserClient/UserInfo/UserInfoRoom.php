<?php

namespace Timetorock\LaravelRocketChat\Client\Responses\UserClient\UserInfo;

class UserInfoRoom
{
    private string $roomID;
    private string $roomRID;
    private string $name;
    private string $t;
    private int    $unread;
    private array  $roles;

    public function __construct(
        string $roomID,
        string $roomRID,
        string $name,
        string $t,
        int    $unread,
        array  $roles
    ) {
        $this->roomID = $roomID;
        $this->roomRID = $roomRID;
        $this->name = $name;
        $this->t = $t;
        $this->unread = $unread;
        $this->roles = $roles;
    }

    public function getRoomID(): string
    {
        return $this->roomID;
    }

    public function getRoomRID(): string
    {
        return $this->roomRID;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getT(): string
    {
        return $this->t;
    }

    public function getUnread(): int
    {
        return $this->unread;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }
}