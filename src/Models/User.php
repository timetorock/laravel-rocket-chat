<?php

namespace Timetorock\LaravelRocketChat\Models;

/**
 * Class User
 * @package Timetorock\LaravelRocketChat\Models
 */
class User extends Entity
{
    const DEFAULT_USER_ROLE = 'user';

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $email;

    /**
     * @var array
     */
    private $emails;

    /**
     * @var array
     */
    private $roles = [self::DEFAULT_USER_ROLE];

    /**
     * @var bool
     */
    private $active = true;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $type;

    /**
     * @var bool
     */
    private $joinDefaultChannels = true;

    /**
     * @var bool
     */
    private $requirePasswordChange = false;

    /**
     * @var bool
     */
    private $sendWelcomeEmail = false;

    /**
     * @var bool
     */
    private $verified = false;

    /**
     * @var string
     */
    private $authToken;

    /**
     * @var array
     */
    protected $fillable = [
        "id", "username", "password", "name", "email", "roles", "active",
        "joinDefaultChannels", "requirePasswordChange", "sendWelcomeEmail", "verified",
    ];

    /** Getters and Setters */
    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     *
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }


    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return array
     */
    public function getEmails()
    {
        return $this->emails;
    }

    /**
     * @param array $emails
     */
    public function setEmails($emails)
    {
        $this->emails = $emails;
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
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     *
     * @return $this
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param bool $active
     *
     * @return User
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return bool
     */
    public function isJoinDefaultChannels()
    {
        return $this->joinDefaultChannels;
    }

    /**
     * @param bool $joinDefaultChannels
     *
     * @return User
     */
    public function setJoinDefaultChannels($joinDefaultChannels)
    {
        $this->joinDefaultChannels = $joinDefaultChannels;

        return $this;
    }

    /**
     * @return bool
     */
    public function isRequirePasswordChange()
    {
        return $this->requirePasswordChange;
    }

    /**
     * @param bool $requirePasswordChange
     *
     * @return User
     */
    public function setRequirePasswordChange($requirePasswordChange)
    {
        $this->requirePasswordChange = $requirePasswordChange;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSendWelcomeEmail()
    {
        return $this->sendWelcomeEmail;
    }

    /**
     * @param bool $sendWelcomeEmail
     *
     * @return User
     */
    public function setSendWelcomeEmail($sendWelcomeEmail)
    {
        $this->sendWelcomeEmail = $sendWelcomeEmail;

        return $this;
    }

    /**
     * @return bool
     */
    public function isVerified()
    {
        return $this->verified;
    }

    /**
     * @param bool $verified
     *
     * @return User
     */
    public function setVerified($verified)
    {
        $this->verified = $verified;

        return $this;
    }

    /**
     * @param string $authToken
     */
    public function setAuthToken($authToken)
    {
        $this->authToken = $authToken;
    }

    /**
     * @return string
     */
    public function getAuthToken()
    {
        return $this->authToken;
    }

    /**
     * Return available user info.
     * @return array
     */
    public function getUserData()
    {
        return [
            'id'       => $this->id,
            'username' => $this->username,
            'name'     => $this->name,
            'email'    => $this->email,
            'emails'   => $this->emails,
            'roles'    => $this->roles,
            'active'   => $this->active,
            'status'   => $this->status,
            'type'     => $this->type,
        ];
    }
}
