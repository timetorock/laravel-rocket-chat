<?php

namespace Timetorock\LaravelRocketChat\Models;


class Integration extends Entity
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var boolean
     */
    protected $enabled;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $event;

    /**
     * @var array
     */
    protected $urls;

    /**
     * @var boolean
     */
    protected $scriptEnabled;

    /**
     * @var string
     */
    protected $userId;

    /**
     * @var array
     */
    protected $channel;

    /**
     * @var array
     */
    protected array $fillable = ['type', 'name', 'enabled', 'username', 'event', 'urls', 'scriptEnabled', 'userId', 'channel'];

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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param string $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * @return array
     */
    public function getUrls()
    {
        return $this->urls;
    }

    /**
     * @param array $urls
     */
    public function setUrls($urls)
    {
        $this->urls = $urls;
    }

    /**
     * @return boolean
     */
    public function getScriptEnabled()
    {
        return $this->scriptEnabled;
    }

    /**
     * @param boolean $scriptEnabled
     */
    public function setScriptEnabled($scriptEnabled)
    {
        $this->scriptEnabled = $scriptEnabled;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param string $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return array
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param array $channel
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;
    }
}