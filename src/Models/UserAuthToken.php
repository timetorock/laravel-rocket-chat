<?php


namespace Timetorock\LaravelRocketChat\Models;


class UserAuthToken
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $token;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * UserAuthToken constructor.
     *
     * @param string $id
     * @param string $token
     */
    public function __construct(string $id, string $token)
    {
        $this->id = $id;
        $this->token = $token;
    }
}