<?php

namespace App\Services;

use App\Exceptions\ChatAlreadyExists;
use App\Exceptions\NoStartMessageFoundForToken;
use App\Models\Chat;
use Illuminate\Support\Str;

class TokenTelegramBotInteractor
{
    private $updates;
    private ?array $chat = null;

    public function interacted($updates): Chat
    {
        return $this->setUpdates($updates)
            ->findStartMessageForMyToken()
            ->validateIfChatExists()
            ->createChat();
    }

    private function createChat(): Chat
    {
        $chat = $this->chat['message']['chat'];
        $username = $chat['username'];
        $chat_id = $chat['id'];

        return auth()->user()
            ->chats()
            ->create([
                'chat_id' => $chat_id,
                'username' => $username,
            ]);
    }

    private function validateIfChatExists(): self
    {
        $chat = $this->chat['message']['chat'];
        $username = $chat['username'];
        $chat_id = $chat['id'];

        if ($this->chatExists($chat_id)) {
            throw new ChatAlreadyExists(__(':username is already linked with Chat ID of :chat_id', ['username' => $username, 'chat_id' => $chat_id]));
        }

        return $this;
    }

    private function chatExists(mixed $chat_id): mixed
    {
        return auth()->user()
            ->chats()
            ->where('chat_id', $chat_id)
            ->exists();
    }

    private function findStartMessageForMyToken(): self
    {
        $token = $this->token();
        foreach ($this->updates as $result) {
            $text = Str::of($result['message']['text']);

            if ($text->startsWith('/start') && $text->endsWith($token)) {
                $this->chat = $result;
                break;
            }
        }

        if ($this->chat == null) {
            throw new NoStartMessageFoundForToken(__('No message found from you!'));
        }

        return $this;
    }

    public function token()
    {
        return my_telegram_token();
    }

    private function setUpdates($updates)
    {
        $this->updates = $updates;

        return $this;
    }
}
