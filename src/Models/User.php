<?php

namespace Timetorock\LaravelRocketChat\Models;

use Timetorock\LaravelRocketChat\Client\Responses\UserClient\UserInfo\UserInfoRooms;

/**
 * Class User
 * @package Timetorock\LaravelRocketChat\Models
 */
class User extends Entity
{
    const DEFAULT_USER_ROLE = 'user';

    protected string        $id                    = '';
    protected string        $username              = '';
    protected string        $password              = '';
    protected string        $name                  = '';
    protected string        $email                 = '';
    protected array         $emails                = [];
    protected array         $roles                 = [self::DEFAULT_USER_ROLE];
    protected bool          $active                = true;
    protected string        $status                = '';
    protected string        $type                  = '';
    protected string        $authToken             = '';
    protected string        $avatarUrl             = '';
    protected bool          $joinDefaultChannels   = true;
    protected bool          $requirePasswordChange = false;
    protected bool          $sendWelcomeEmail      = false;
    protected bool          $verified              = false;
    protected array         $settings              = [];
    protected array         $customFields          = [];
    protected UserInfoRooms $userRooms;

    /**
     * @var array
     */
    protected array $fillable = [
        'id',
        'avatarUrl',
        'username',
        'password',
        'name',
        'email',
        'roles',
        'active',
        'joinDefaultChannels',
        'requirePasswordChange',
        'sendWelcomeEmail',
        'verified',
        'customFields',
    ];

    public function __construct(array $userData = [])
    {
        $this->userRooms = new UserInfoRooms([]);

        parent::__construct($userData);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId($id): User
    {
        $this->id = $id;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername($username): User
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword($password): User
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }


    public function setEmail($email): User
    {
        $this->email = $email;

        return $this;
    }

    public function getEmails(): array
    {
        return $this->emails;
    }

    public function setEmails(array $emails): User
    {
        $this->emails = $emails;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName($name): User
    {
        $this->name = $name;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles($roles): User
    {
        $this->roles = $roles;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive($active): User
    {
        $this->active = $active;

        return $this;
    }

    public function isJoinDefaultChannels(): bool
    {
        return $this->joinDefaultChannels;
    }

    public function setJoinDefaultChannels($joinDefaultChannels): User
    {
        $this->joinDefaultChannels = $joinDefaultChannels;

        return $this;
    }

    public function isRequirePasswordChange(): bool
    {
        return $this->requirePasswordChange;
    }

    public function setRequirePasswordChange($requirePasswordChange): User
    {
        $this->requirePasswordChange = $requirePasswordChange;

        return $this;
    }

    public function isSendWelcomeEmail(): bool
    {
        return $this->sendWelcomeEmail;
    }

    public function setSendWelcomeEmail($sendWelcomeEmail): User
    {
        $this->sendWelcomeEmail = $sendWelcomeEmail;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->verified;
    }

    public function setVerified($verified): User
    {
        $this->verified = $verified;

        return $this;
    }

    public function setAuthToken(string $authToken): User
    {
        $this->authToken = $authToken;

        return $this;
    }

    public function getAuthToken(): string
    {
        return $this->authToken;
    }

    public function getCustomFields(): array
    {
        return $this->customFields;
    }

    public function setCustomFields(array $customFields): User
    {
        $this->customFields = $customFields;

        return $this;
    }

    public function getAvatarUrl(): string
    {
        return $this->avatarUrl;
    }

    public function getUserRooms(): UserInfoRooms
    {
        return $this->userRooms;
    }

    public function setUserRooms(UserInfoRooms $userRooms): void
    {
        $this->userRooms = $userRooms;
    }

    /**
     * Return available user info.
     * @return array
     */
    public function getUserData(): array
    {
        return [
            'id'           => $this->id,
            'username'     => $this->username,
            'name'         => $this->name,
            'email'        => $this->email,
            'emails'       => $this->emails,
            'roles'        => $this->roles,
            'active'       => $this->active,
            'status'       => $this->status,
            'type'         => $this->type,
            'settings'     => $this->settings,
            'customFields' => $this->customFields,
            'avatarUrl'    => $this->avatarUrl,
            'userRooms'    => $this->userRooms,
        ];
    }
}
