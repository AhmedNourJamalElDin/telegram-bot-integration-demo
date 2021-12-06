<?php

namespace Tests\Unit;

use App\Models\Chat;
use App\Services\UsernameTelegramBotWebHookHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsernameTelegramBotWebHookHandlerTest extends TestCase
{
    use RefreshDatabase;

    private UsernameTelegramBotWebHookHandler $handler;

    public function test_linkedChat_updated()
    {
        Chat::factory()->createOne([
            'username' => 'AhmedNourJamalElDin',
            'chat_id' => null,
        ]);

        $updates = [
            [
                'message' => [
                    'chat' => [
                        'id' => 1234,
                        'username' => 'AhmedNourJamalElDin',
                    ],
                    'text' => '/start',
                ],
            ],
        ];
        $this->handler->handle($updates);
        $this->assertDatabaseCount('chats', 1)
            ->assertDatabaseHas('chats', [
                'chat_id' => 1234,
                'username' => 'AhmedNourJamalElDin',
            ]);
    }

    public function test_noChats_noChatsUpdated()
    {
        $updates = [
            [
                'message' => [
                    'chat' => [
                        'id' => 1234,
                        'username' => 'AhmedNourJamalElDin',
                    ],
                    'text' => '/start AZ91aHVqR1Kz6hsluqptJFp6Lqw1gIt7PBD9oJkeQTLqvrnp1HoRV3aQlpICo5mm',
                ],
            ],
        ];
        $this->handler->handle($updates);
        $this->assertDatabaseCount('chats', 0);
    }

    public function test_notStartMessage_noChatsUpdated()
    {
        Chat::factory()->createOne([
            'username' => 'AhmedNourJamalElDin',
            'chat_id' => null,
            'user_id' => auth()->id(),
        ]);

        $updates = [
            [
                'message' => [
                    'chat' => [
                        'id' => 1234,
                        'username' => 'AhmedNourJamalElDin',
                    ],
                    'text' => '/end',
                ],
            ],
        ];
        $this->handler->handle($updates);
        $this->assertDatabaseCount('chats', 1)
            ->assertDatabaseHas('chats', [
                'username' => 'AhmedNourJamalElDin',
                'chat_id' => null,
                'user_id' => auth()->id(),
            ]);
    }

    public function test_existsChatId_noChatsUpdated()
    {
        Chat::factory()->createOne([
            'username' => 'AhmedNourJamalElDin',
            'chat_id' => 'abc',
            'user_id' => auth()->id(),
        ]);

        $updates = [
            [
                'message' => [
                    'chat' => [
                        'id' => 1234,
                        'username' => 'AhmedNourJamalElDin',
                    ],
                    'text' => '/start AZ91aHVqR1Kz6hsluqptJFp6Lqw1gIt7PBD9oJkeQTLqvrnp1HoRV3aQlpICo5mm',
                ],
            ],
        ];

        $this->handler->handle($updates);
        $this->assertDatabaseCount('chats', 1)
            ->assertDatabaseHas('chats', [
                'username' => 'AhmedNourJamalElDin',
                'chat_id' => 'abc'
            ]);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->handler = new UsernameTelegramBotWebHookHandler();
    }
}
