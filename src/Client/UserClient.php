<?php

namespace Timetorock\LaravelRocketChat\Client;

use Exception;
use Httpful\Exception\ConnectionErrorException;
use Timetorock\LaravelRocketChat\Client\Responses\UserClient\UserInfo\UserInfoRoom;
use Timetorock\LaravelRocketChat\Client\Responses\UserClient\UserInfo\UserInfoRooms;
use Timetorock\LaravelRocketChat\Exceptions\UserActionException;
use Timetorock\LaravelRocketChat\Models\User;

class UserClient extends Client
{

    const USER = 'user';

    const API_PATH_LOGIN             = 'login';
    const API_PATH_LOGOUT            = 'logout';
    const API_PATH_ME                = 'me';
    const API_PATH_USER_CREATE       = 'users.create';
    const API_PATH_USER_UPDATE       = 'users.update';
    const API_PATH_USER_INFO         = 'users.info';
    const API_PATH_USER_DELETE       = 'users.delete';
    const API_PATH_USER_LIST         = 'users.list';
    const API_PATH_USER_CREATE_TOKEN = 'users.createToken';
    const API_PATH_USER_GET_AVATAR   = 'users.getAvatar';
    const API_PATH_USER_GET_PRESENCE = 'users.getPresence';
    const API_PATH_USER_REGISTER     = 'users.register';
    const API_PATH_USER_RESET_AVATAR = 'users.resetAvatar';
    const API_PATH_USER_SET_AVATAR   = 'users.setAvatar';


    /**
     * @param bool $useAsClientAuth Future requests will be done on behalf of this user.
     *
     * @return User
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function login(User $user, bool $useAsClientAuth = false): User
    {
        $response = $this->request()
            ->post($this->apiUrl(self::API_PATH_LOGIN))
            ->body([
                self::USER => $user->getUsername(),
                'password' => $user->getPassword(),
            ])
            ->send();

        $data = $this->handleResponse($response, new UserActionException(), ['data']);

        $user = new User();
        $user->setId($data->userId)->setAuthToken($data->authToken);

        if ($useAsClientAuth) {
            $this->setAuth($user->getId(), $user->getAuthToken());
        }

        return $user;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function logout()
    {
        $response = $this->request()->get($this->apiUrl(self::API_PATH_LOGOUT))->send();
        return $this->handleResponse($response, new UserActionException(), ['data', 'message']);
    }

    /**
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function me(): User
    {
        $response = $this->request()->get($this->apiUrl(self::API_PATH_ME))->send();
        $body = $this->handleResponse($response, new UserActionException());

        $user = new User();
        $user->fill([
            'id'        => $body->_id,
            'name'      => $body->name,
            'email'     => $body->emails[0]->address,
            'emails'    => $body->emails,
            'username'  => $body->username,
            'active'    => $body->active,
            'roles'     => $body->roles ?? [],
            'status'    => $body->status,
            'type'      => $body->type,
            'settings'  => $body->settings ?? [],
            'avatarUrl' => $body->avatarUrl ?? null,
        ]);

        if (!empty($userData->customFields)) {
            $user->setCustomFields((array) $userData->customFields);
        }

        return $user;
    }

    /**
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function create(User $user): User
    {
        $response = $this->request()->post($this->apiUrl(self::API_PATH_USER_CREATE))
            ->body($user->getFillableData())
            ->send();

        $userData = $this->handleResponse($response, new UserActionException(), [self::USER]);

        $user = new User();
        $user->fill([
            'id'       => $userData->_id,
            'name'     => $userData->name,
            'email'    => $userData->emails[0]->address,
            'emails'   => $userData->emails,
            'username' => $userData->username,
            'active'   => $userData->active,
            'roles'    => $userData->roles,
            'status'   => $userData->status,
            'type'     => $userData->type,
            'settings' => $userData->settings ?? [],
        ]);

        if (!empty($userData->customFields)) {
            $user->setCustomFields((array) $userData->customFields);
        }

        return $user;
    }

    /**
     * @param User $user
     *
     * @return User
     * @throws ConnectionErrorException
     * @throws UserActionException
     * @throws Exception
     */
    public function update(User $user)
    {
        if (empty($user->getId())) {
            throw new UserActionException('user ID is required for user update');
        }

        $postData = [
            'userId' => $user->getId(),
        ];

        foreach ($user->getFillableData() as $key => $value) {
            $postData['data'][ $key ] = $value;
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_USER_UPDATE))
            ->body($postData)
            ->send();

        $userData = $this->handleResponse($response, new UserActionException(), [self::USER]);

        $user = new User();
        $user->fill([
            'id'       => $userData->_id,
            'name'     => $userData->name,
            'email'    => $userData->emails[0]->address,
            'emails'   => $userData->emails,
            'username' => $userData->username,
            'active'   => $userData->active,
            'roles'    => $userData->roles,
            'status'   => $userData->status,
            'type'     => $userData->type,
        ]);

        return $user;
    }

    /**
     * @param string $id        Can be ID or Username
     * @param string $paramType
     * @param bool   $userRooms Will load a list of user rooms with counters.
     *
     * @return User
     * @throws ConnectionErrorException
     * @throws UserActionException
     */
    public function info(
        string $id,
        string $paramType = 'userId',
        bool   $userRooms = false
    ): User {
        if (!in_array($paramType, ['userId', 'username'])) {
            throw new UserActionException('bad method parameter value');
        }

        if (!$id) {
            throw new UserActionException('user ID not specified');
        }

        $getParameters = [$paramType => $id];

        if ($userRooms) {
            $getParameters['userRooms'] = 1;
        }

        $response = $this->request()->get($this->apiUrl(self::API_PATH_USER_INFO, $getParameters))->send();
        $userData = $this->handleResponse($response, new UserActionException(), [self::USER]);

        $user = new User();
        $user->fill([
            'id'       => $userData->_id,
            'name'     => $userData->name,
            'email'    => $userData->emails[0]->address,
            'emails'   => $userData->emails,
            'username' => $userData->username,
            'active'   => $userData->active,
            'roles'    => $userData->roles,
            'status'   => $userData->status,
            'type'     => $userData->type,
        ]);

        if (!empty($userData->rooms)) {
            $user->setUserRooms(new UserInfoRooms(
                $userData->rooms
            ));
        }

        return $user;
    }

    /**
     * @param User $user
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws UserActionException
     * @throws Exception
     */
    public function delete(User $user)
    {
        if (!$user->getId()) {
            throw new UserActionException('user ID not specified');
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_USER_DELETE))
            ->body(['userId' => $user->getId()])
            ->send();

        return $this->handleResponse($response, new UserActionException());
    }

    /**
     * @param array $params
     * @param bool  $onlyUsers
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function list(array $params = [], $onlyUsers = true)
    {
        $response = $this->request()->get($this->apiUrl(self::API_PATH_USER_LIST, $params))->send();

        $filter = ['users'];

        if (!$onlyUsers) {
            $filter = [];
        }

        return $this->handleResponse($response, new UserActionException(), $filter);
    }

    /**
     * @param string $id Can be ID or Username
     * @param string $paramType
     *
     * @return User
     * @throws UserActionException
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function createToken($id, $paramType = 'userId')
    {
        if (!in_array($paramType, ['userId', 'username'])) {
            throw new UserActionException('bad method parameter value');
        }

        if (!$id) {
            throw new UserActionException('user ID not specified');
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_USER_CREATE_TOKEN))
            ->body([$paramType => $id])
            ->send();

        $authToken = $this->handleResponse($response, new UserActionException(), ['data', 'authToken']);

        $user = new User();
        $user->setAuthToken($authToken);

        return $user;
    }

    /**
     * @param string $id Can be ID or Username
     * @param string $paramType
     *
     * @return string
     * @throws UserActionException
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function getAvatar($id, $paramType = 'userId')
    {
        if (!in_array($paramType, ['userId', 'username'])) {
            throw new UserActionException('bad method parameter value');
        }

        if (!$id) {
            throw new UserActionException('user ID not specified');
        }

        $response = $this->request()->get($this->apiUrl(self::API_PATH_USER_GET_AVATAR, [$paramType => $id]))
            ->send();
        return $this->handleResponse($response, new UserActionException());
    }

    /**
     * @param string $id Can be ID or Username
     * @param string $paramType
     *
     * @return mixed
     * @throws UserActionException
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function getPresence($id, $paramType = 'userId')
    {
        if (!in_array($paramType, ['userId', 'username'])) {
            throw new UserActionException('bad method parameter value');
        }

        if (!$id) {
            throw new UserActionException('user ID not specified');
        }

        $response = $this->request()->get($this->apiUrl(self::API_PATH_USER_GET_PRESENCE, [$paramType => $id]))
            ->send();

        return $this->handleResponse($response, new UserActionException(), ['presence']);
    }

    /**
     * @param User $user
     *
     * @return User
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function register(User $user)
    {
        $postData = $user->getFillableData();
        $postData['pass'] = $user->getPassword();
        unset($postData['password']);

        $response = $this->request()->post($this->apiUrl(self::API_PATH_USER_REGISTER))
            ->body($postData)
            ->send();

        $userData = $this->handleResponse($response, new UserActionException(), [self::USER]);

        $user = new User();
        $user->fill([
            'id'       => $userData->_id,
            'name'     => $userData->name,
            'email'    => $userData->emails[0]->address,
            'emails'   => $userData->emails,
            'username' => $userData->username,
            'active'   => $userData->active,
            'roles'    => $userData->roles,
            'status'   => $userData->status,
            'type'     => $userData->type,
        ]);

        return $user;
    }

    /**
     * @param string $id Can be ID or Username
     * @param string $paramType
     *
     * @return mixed
     * @throws UserActionException
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function resetAvatar($id, $paramType = 'userId')
    {
        if (!in_array($paramType, ['userId', 'username'])) {
            throw new UserActionException('bad method parameter value');
        }

        if (!$id) {
            throw new UserActionException('user ID not specified');
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_USER_RESET_AVATAR))
            ->body([$paramType => $id])
            ->send();
        return $this->handleResponse($response, new UserActionException());
    }

    /**
     * @param string $avatarUrl
     * @param string $id Can be ID or Username
     * @param string $paramType
     *
     * @return mixed
     * @throws UserActionException
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function setAvatar($avatarUrl, $id, $paramType = 'userId')
    {
        if (!in_array($paramType, ['userId', 'username'])) {
            throw new UserActionException('bad method parameter value');
        }

        if (!$id) {
            throw new UserActionException('User ID not specified.');
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_USER_SET_AVATAR))
            ->body([
                'avatarUrl' => $avatarUrl,
                $paramType  => $id,
            ])
            ->send();

        return $this->handleResponse($response, new UserActionException());
    }
}
