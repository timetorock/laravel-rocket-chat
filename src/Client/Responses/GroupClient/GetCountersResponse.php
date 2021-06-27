<?php


namespace Timetorock\LaravelRocketChat\Client\Responses\GroupClient;


class GetCountersResponse
{
    /**
     * @var int
     */
    private $members;

    /**
     * @var int
     */
    private $messages;

    /**
     * @var int
     */
    private $userMentions;

    /**
     * @var int
     */
    private $unreads;

    /**
     * @var string
     */
    private $unreadsFrom;

    /**
     * @var string
     */
    private $latest;

    /**
     * @var bool
     */
    private $joined;

    /**
     * @var bool
     */
    private $success;

    /**
     * OrderGroupCountersResponse constructor.
     *
     * @param int    $members
     * @param int    $messages
     * @param int    $userMentions
     * @param int    $unreads
     * @param string $unreadsFrom
     * @param string $latest
     * @param bool   $joined
     * @param bool   $success
     */
    public function __construct(
        int $members,
        int $messages,
        int $userMentions,
        int $unreads,
        string $unreadsFrom,
        string $latest,
        bool $joined,
        bool $success
    ) {
        $this->members = $members;
        $this->messages = $messages;
        $this->userMentions = $userMentions;
        $this->unreads = $unreads;
        $this->unreadsFrom = $unreadsFrom;
        $this->latest = $latest;
        $this->joined = $joined;
        $this->success = $success;
    }

    /**
     * @return int
     */
    public function getMembers(): int
    {
        return $this->members;
    }

    /**
     * @return int
     */
    public function getMessages(): int
    {
        return $this->messages;
    }

    /**
     * @return int
     */
    public function getUserMentions(): int
    {
        return $this->userMentions;
    }

    /**
     * @return int
     */
    public function getUnreads(): int
    {
        return $this->unreads;
    }

    /**
     * @return string
     */
    public function getUnreadsFrom(): string
    {
        return $this->unreadsFrom;
    }

    /**
     * @return string
     */
    public function getLatest(): string
    {
        return $this->latest;
    }

    /**
     * @return bool
     */
    public function isJoined(): bool
    {
        return $this->joined;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }
}