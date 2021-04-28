<?php

namespace Stephenmudere\Chat\Tests\Feature;

use Chat;
use Stephenmudere\Chat\Models\Conversation;
use Stephenmudere\Chat\Models\Participation;
use Stephenmudere\Chat\Tests\Helpers\Models\Client;
use Stephenmudere\Chat\Tests\Helpers\Models\User;
use Stephenmudere\Chat\Tests\TestCase;

class ConversationParticipationControllerTest extends TestCase
{
    public function testStore()
    {
        $conversation = factory(Conversation::class)->create();
        $userModel = factory(User::class)->create();
        $clientModel = factory(Client::class)->create();
        $payload = [
            'participants' => [
                ['id' => $userModel->getKey(), 'type' => $userModel->getMorphClass()],
                ['id' => $clientModel->getKey(), 'type' => $clientModel->getMorphClass()],
            ],
        ];

        $this->postJson(route('conversations.participation.store', [$conversation->getKey()]), $payload)
            ->assertStatus(200);

        $this->assertCount(2, $conversation->participants);
    }

    public function testIndex()
    {
        $conversation = factory(Conversation::class)->create();
        $userModel = factory(User::class)->create();
        $clientModel = factory(Client::class)->create();

        Chat::conversation($conversation)->addParticipants([$userModel, $clientModel]);

        $this->getJson(route('conversations.participation.index', [$conversation->getKey()]))
            ->assertStatus(200)
            ->assertJsonCount(2);
    }

    public function testShow()
    {
        $conversation = factory(Conversation::class)->create();
        $userModel = factory(User::class)->create();
        Chat::conversation($conversation)->addParticipants([$userModel]);

        /** @var Participation $participant */
        $participant = $conversation->participants->first();

        $this->getJson(route('conversations.participation.show', [$conversation->getKey(), $participant->getKey()]))
            ->assertStatus(200)
            ->assertJson([
                'messageable_type' => $userModel->getMorphClass(),
            ]);
    }

    public function testDestroy()
    {
        $conversation = factory(Conversation::class)->create();
        $userModel = factory(User::class)->create();
        $clientModel = factory(Client::class)->create();

        Chat::conversation($conversation)->addParticipants([$userModel, $clientModel]);

        $this->assertCount(2, $conversation->participants);

        /** @var Participation $participant */
        $participant = $conversation->participants->first();

        $this->deleteJson(route('conversations.participation.destroy', [$conversation->getKey(), $participant->getKey()]))
            ->assertStatus(200)
            ->assertJsonCount(1);
    }

    public function testUpdate()
    {
        $conversation = factory(Conversation::class)->create();
        $userModel = factory(User::class)->create();
        $clientModel = factory(Client::class)->create();

        Chat::conversation($conversation)->addParticipants([$userModel, $clientModel]);

        $this->assertCount(2, $conversation->participants);

        /** @var Participation $participant */
        $participant = $conversation->participants->first();

        $payload = [
            'settings' => [
                'mute_mentions' => true,
            ],
        ];

        $this->putJson(
            route('conversations.participation.update', [$conversation->getKey(), $participant->getKey()]),
            $payload
        )
            ->assertStatus(200)
            ->assertJson(['settings' => $payload['settings']]);
    }
}
