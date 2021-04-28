<?php

namespace Stephenmudere\Chat\Eventing;

use Stephenmudere\Chat\Models\Message;

class AllParticipantsDeletedMessage extends Event
{
    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }
}
