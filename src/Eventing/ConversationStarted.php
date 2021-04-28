<?php

namespace Stephenmudere\Chat\Eventing;

use Stephenmudere\Chat\Models\Conversation;

class ConversationStarted extends Event
{
    /**
     * @var Conversation
     */
    public $conversation;

    public function __construct(Conversation $conversation)
    {
        $this->conversation = $conversation;
    }
}
