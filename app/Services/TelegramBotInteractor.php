<?php

namespace App\Services;

use App\Exceptions\ChatAlreadyExists;
use App\Exceptions\NoStartMessageFoundForToken;
use App\Models\Chat;
use Illuminate\Support\Str;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramBotInteractor
{
    public function interacted(): Chat
    {
        $updates = Telegram::getUpdates();

        $expected_result = $this->findStartMessageForMyToken($updates);

        if ($expected_result == null) {
            throw new NoStartMessageFoundForToken(__('No message found from you!'));
        }

        $chat = $expected_result['message']['chat'];
        $username = $chat['username'];
        $chat_id = $chat['id'];

        if ($this->chatExists($chat_id)) {
            throw new ChatAlreadyExists(__(':username is already linked with Chat ID of :chat_id', ['username' => $username, 'chat_id' => $chat_id]));
        }

        return $this->createChat($chat_id, $username);
    }

    private function findStartMessageForMyToken(array $updates)
    {
        $token = $this->token();
        foreach ($updates as $result) {
            $text = Str::of($result['message']['text']);

            if ($text->startsWith('/start') && $text->endsWith($token)) {
                return $result;
            }
        }
    }

    public function token()
    {
        return my_telegram_token();
    }

    private function chatExists(mixed $chat_id): mixed
    {
        return auth()->user()
            ->chats()
            ->where('chat_id', $chat_id)
            ->exists();
    }

    private function createChat(mixed $chat_id, mixed $username)
    {
        return auth()->user()
            ->chats()
            ->create([
                'chat_id' => $chat_id,
                'username' => $username,
            ]);
    }
}
