<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Collection;

class TokenTelegramBotWebHookHandler
{
    private Collection $updates;

    public function handle($updates)
    {
        $this->begin()
            ->setUpdates($updates)
            ->filterByMatcher()
            ->filterByChatIdExistent()
            ->filterAndAddUserId()
            ->createChats();
    }

    private function createChats()
    {
        Chat::query()
            ->insert($this->updates->toArray());
    }

    private function filterAndAddUserId(): self
    {
        $extractor = new TokenExtractor();

        $chats_without_users_ids = $this->updates
            ->pluck('message')
            ->map(fn ($message) => [
                'chat_id' => $message['chat']['id'],
                'username' => $message['chat']['username'],
                'token' => $extractor->extract($message['text']),
            ]);

        $users = User::query()
            ->whereIn('telegram_token', $chats_without_users_ids->pluck('token'))
            ->pluck('id', 'telegram_token');

        $this->updates = $chats_without_users_ids->map(function ($chat) use ($users) {
            $token = $chat['token'];

            if (! $users->has($token)) {
                return null;
            }
            unset($chat['token']);

            return array_merge($chat, [
                'user_id' => $users[$token],
            ]);
        })->filter(fn ($item) => $item);

        return $this;
    }

    private function filterByChatIdExistent(): self
    {
        $chats = Chat::query()
            ->whereIn('chat_id', $this->updates->pluck('message.chat.id'))
            ->pluck('chat_id');

        $this->updates = $this->updates->filter(fn ($update) => ! $chats->contains($update['message']['chat']['id']));

        return $this;
    }

    private function filterByMatcher(): self
    {
        $matcher = new StartTokenMessageMatcher();
        $this->updates = $this->updates->filter(fn ($update) => $matcher->match($update['message']['text']));

        return $this;
    }

    private function setUpdates($updates): self
    {
        $this->updates = collect($updates);

        return $this;
    }

    private function begin(): self
    {
        return $this;
    }
}
