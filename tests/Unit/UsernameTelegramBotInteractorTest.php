<?php

namespace Tests\Unit;

use App\Exceptions\NoFreeChatsForYourUsername;
use App\Exceptions\NoMessagesFoundForUsername;
use App\Models\Chat;
use App\Models\User;
use App\Services\UsernameTelegramBotInteractor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsernameTelegramBotInteractorTest extends TestCase
{
    use RefreshDatabase;

    private UsernameTelegramBotInteractor $interactor;

    public function test_linkedChat_updated()
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
                    'text' => '/start',
                ],
            ],
        ];

        $this->interactor->interacted($updates);
        $this->assertDatabaseCount('chats', 1)
            ->assertDatabaseHas('chats', [
                'username' => 'AhmedNourJamalElDin',
                'chat_id' => '1234',
            ]);
    }

    public function test_noStartMessage_noChatsUpdated()
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

        $this->expectException(NoMessagesFoundForUsername::class);

        $this->interactor->interacted($updates);
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
                    'text' => '/start',
                ],
            ],
        ];

        $this->expectException(NoFreeChatsForYourUsername::class);

        $this->interactor->interacted($updates);
        $this->assertDatabaseCount('chats', 1)
            ->assertDatabaseHas('chats', [
                'username' => 'AhmedNourJamalElDin',
                'chat_id' => 'abc',
                'user_id' => auth()->id(),
            ]);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->createOne());

        $this->interactor = new UsernameTelegramBotInteractor();
    }
}
