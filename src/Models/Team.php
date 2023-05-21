<?php

namespace Timetorock\LaravelRocketChat\Models;

/**
 * Class Room
 * @package Timetorock\LaravelRocketChat\Models
 */
class Team extends Entity
{
    /**
     * @var string
     */
     protected $id;

    /**
     * @var string
     */
     protected $name;

    /**
     * @var array
     */
     protected $members = [];

    /**
     * @var bool
     */
     protected $room = [];

    /**
     * Types available:
     * 0: Public
     * 1: Private
     * Private by default
     * @var string
     */
     protected $type = 1;

    /**
     * @var array
     */
    protected array $fillable = ['id', 'name', 'members', 'room', 'type'];

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getMembers(): array
    {
        return $this->members;
    }

    /**
     * @param array $members
     */
    public function setMembers(array $members)
    {
        $this->members = $members;
    }

    /**
     * @return bool
     */
    public function getRoom(): array
    {
        return $this->room;
    }

    /**
     * @param array $room
     */
    public function setRoom(array $room)
    {
        $this->room = $room;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * Return available team info.
     * @return array
     */
    public function getTeamData()
    {
        return [
            'id'      => $this->id,
            'name'    => $this->name,
            'type'    => $this->type,
        ];
    }
}
