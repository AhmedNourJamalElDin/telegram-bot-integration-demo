<?php

namespace Tests\Unit;

use App\Models\Chat;
use App\Models\User;
use App\Services\TelegramBotWebHookHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TelegramBotWebHookHandlerTest extends TestCase
{
    use RefreshDatabase;

    private TelegramBotWebHookHandler $handler;

    public function test_newChat_created()
    {
        User::factory()->createOne([
            'telegram_token' => 'AZ91aHVqR1Kz6hsluqptJFp6Lqw1gIt7PBD9oJkeQTLqvrnp1HoRV3aQlpICo5mm',
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
        $this->handler->handler($updates);
        $this->assertDatabaseCount('chats', 1);
    }

    public function test_noUsers_noChatsCreated()
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
        $this->handler->handler($updates);
        $this->assertDatabaseCount('chats', 0);
    }

    public function test_tokenMismatchesPattern_noChatsCreated()
    {
        $updates = [
            [
                'message' => [
                    'chat' => [
                        'id' => 1234,
                        'username' => 'AhmedNourJamalElDin',
                    ],
                    'text' => '/start 1',
                ],
            ],
        ];
        $this->handler->handler($updates);
        $this->assertDatabaseCount('chats', 0);
    }

    public function test_existsChatId_noChatsCreated()
    {
        Chat::create(['chat_id' => '1234']);

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

        $this->handler->handler($updates);
        $this->assertDatabaseCount('chats', 1);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->handler = new TelegramBotWebHookHandler();
    }
}
