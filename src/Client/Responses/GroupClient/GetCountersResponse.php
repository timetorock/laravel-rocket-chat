<?php


namespace Timetorock\LaravelRocketChat\Client\Responses\GroupClient;


class GetCountersResponse
{
    private int    $members;
    private int    $messages;
    private int    $userMentions;
    private int    $unreads;
    private string $unreadsFrom;
    private string $latest;
    private bool   $joined;
    private bool   $success;

    public function __construct(
        int    $members,
        int    $messages,
        int    $userMentions,
        int    $unreads,
        string $unreadsFrom,
        string $latest,
        bool   $joined,
        bool   $success
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

    public function getMembers(): int
    {
        return $this->members;
    }

    public function getMessages(): int
    {
        return $this->messages;
    }

    public function getUserMentions(): int
    {
        return $this->userMentions;
    }

    public function getUnreads(): int
    {
        return $this->unreads;
    }

    public function getUnreadsFrom(): string
    {
        return $this->unreadsFrom;
    }

    public function getLatest(): string
    {
        return $this->latest;
    }

    public function isJoined(): bool
    {
        return $this->joined;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }
}