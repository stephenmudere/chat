<?php

namespace Stephenmudere\Chat\Eventing;

use Stephenmudere\Chat\Models\Conversation;

class AllParticipantsClearedConversation
{
    public $conversation;

    public function __construct(Conversation $conversation)
    {
        $this->conversation = $conversation;
    }
}
