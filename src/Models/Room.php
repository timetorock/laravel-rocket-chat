<?php

namespace Timetorock\LaravelRocketChat\Models;

/**
 * Class Room
 * @package Timetorock\LaravelRocketChat\Models
 */
class Room extends Entity
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
    protected $readOnly = false;

    /**
     * Types available:
     * d: Direct chat
     * c: Chat
     * p: Private chat
     * l: Live chat
     * Private by default
     * @var string
     */
    protected $type = 'p';

    /**
     * @var array
     */
    protected array $fillable = ['id', 'name', 'members', 'readOnly', 'type'];

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
    public function isReadOnly(): bool
    {
        return $this->readOnly;
    }

    /**
     * @param bool $readOnly
     */
    public function setReadOnly(bool $readOnly)
    {
        $this->readOnly = $readOnly;
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
     * Return available group info.
     * @return array
     */
    public function getGroupData()
    {
        return [
            'id'      => $this->id,
            'name'    => $this->name,
            'members' => $this->members,
            'type'    => $this->type,
        ];
    }
}
