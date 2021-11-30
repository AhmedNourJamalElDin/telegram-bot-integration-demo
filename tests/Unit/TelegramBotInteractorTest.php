<?php

namespace Tests\Unit;

use App\Exceptions\ChatAlreadyExists;
use App\Exceptions\NoStartMessageFoundForToken;
use App\Models\Chat;
use App\Models\User;
use App\Services\TelegramBotInteractor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Telegram\Bot\Laravel\Facades\Telegram;
use Tests\TestCase;

class TelegramBotInteractorTest extends TestCase
{
    use RefreshDatabase;

    private TelegramBotInteractor $interactor;

    public function test_newChat_created()
    {
        auth()->user()->update([
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
        $this->mockGetUpdatesResponse($updates);

        $this->interactor->interacted();
        $this->assertDatabaseCount('chats', 1);
    }

    protected function mockGetUpdatesResponse($updates): void
    {
        Telegram::shouldReceive('getUpdates')
            ->andReturn($updates)
            ->once();
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
        $this->mockGetUpdatesResponse($updates);
        $this->expectException(NoStartMessageFoundForToken::class);

        $this->interactor->interacted();
        $this->assertDatabaseCount('chats', 0);
    }

    public function test_existsChatId_noChatsCreated()
    {
        Chat::create([
            'chat_id' => '1234',
            'user_id' => auth()->id(),
        ]);

        $updates = [
            [
                'message' => [
                    'chat' => [
                        'id' => 1234,
                        'username' => 'AhmedNourJamalElDin',
                    ],
                    'text' => '/start '.my_telegram_token(),
                ],
            ],
        ];

        $this->mockGetUpdatesResponse($updates);
        $this->expectException(ChatAlreadyExists::class);

        $this->interactor->interacted();
        $this->assertDatabaseCount('chats', 1);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->createOne());

        $this->interactor = new TelegramBotInteractor();
    }
}
