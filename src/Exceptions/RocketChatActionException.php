<?php

namespace Timetorock\LaravelRocketChat\Exceptions;

use Exception;

class RocketChatActionException extends Exception
{
    public function __toString()
    {
        return $this->getMessage();
    }

    public function setMessage($message) {
        $this->message = $message;
    }
}