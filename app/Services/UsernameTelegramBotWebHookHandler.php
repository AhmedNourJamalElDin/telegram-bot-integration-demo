<?php

namespace App\Services;

use App\Models\Chat;
use Illuminate\Support\Collection;

class UsernameTelegramBotWebHookHandler
{
    private Collection $updates;

    public function handle($updates)
    {
        $this->begin()
            ->setUpdates($updates)
            ->filterByStartMessage()
            ->extractUsernameAndChatId()
            ->updateChats();
    }

    private function updateChats(): self
    {
        foreach ($this->updates as $update) {
            Chat::query()
                ->where([
                    'username' => $update['username'],
                    'chat_id' => null,
                ])
                ->update([
                    'chat_id' => $update['chat_id'],
                ]);
        }

        return $this;
    }

    private function extractUsernameAndChatId(): self
    {
        $this->updates = $this->updates
            ->pluck('message')
            ->map(fn($message) => [
                'chat_id' => $message['chat']['id'],
                'username' => $message['chat']['username'],
            ]);

        return $this;
    }

    private function filterByStartMessage(): self
    {
        $matcher = new StartMessageMatcher();
        $this->updates = $this->updates->filter(fn($update) => $matcher->match($update['message']['text']));

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
