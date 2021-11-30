<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TelegramBotWebhookControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_routeExistsAndReturnsSuccess()
    {
        $this->actingAs($user = User::factory()->realToken()->createOne());

        $updates = [
            'ok' => true,
            'result' => [
                [
                    'message' => [
                        'chat' => [
                            'id' => 1234,
                            'username' => 'AhmedNourJamalElDin',
                        ],
                        'text' => '/start '.$user->telegram_token,
                    ],
                ],
            ],
        ];

        $this->post(route('telegram-bot.web-hook'), $updates)
            ->assertSuccessful();

        $this->assertDatabaseCount('chats', 1);
    }
}
