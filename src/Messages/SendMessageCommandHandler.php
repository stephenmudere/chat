<?php

namespace Stephenmudere\Chat\Messages;

use Illuminate\Database\Eloquent\Model;
use Stephenmudere\Chat\Eventing\EventDispatcher;
use Stephenmudere\Chat\Models\Message;

class SendMessageCommandHandler
{
    protected $message;
    protected $dispatcher;

    /**
     * @param EventDispatcher $dispatcher The dispatcher
     * @param Message         $message    The message
     */
    public function __construct(EventDispatcher $dispatcher, Message $message)
    {
        $this->dispatcher = $dispatcher;
        $this->message = $message;
    }

    /**
     * Triggers sending the message.
     *
     * @param SendMessageCommand $command The command
     *
     * @return Model
     */
    public function handle(SendMessageCommand $command)
    {
        $message = $this->message->send($command->conversation, $command->body, $command->participant, $command->type);

        $this->dispatcher->dispatch($this->message->releaseEvents());

        return $message;
    }
}
