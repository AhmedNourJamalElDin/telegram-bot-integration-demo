<?php

namespace App\Services;

use App\Exceptions\NoChatsFound;
use App\Exceptions\NoFreeChatsForYourUsername;
use App\Exceptions\NoMessagesFoundForUsername;
use App\Models\Chat;

class UsernameTelegramBotInteractor
{
    private $updates;
    private $chats_without_chat_ids;
    private ?array $chat = null;

    public function interacted($updates): ?Chat
    {
        return $this->setUpdates($updates)
            ->filterByStartMessage()
            ->fetchMyNotLinkedChats()
            ->findMessageForUsernames()
            ->updateChats()
            ->returnChat();
    }

    private function returnChat()
    {
        return Chat::where([
            'username' => $this->getUsernameInChat(),
            'user_id' => auth()->id(),
        ])->first();
    }

    private function getUsernameInChat(): string
    {
        return $this->chat['message']['chat']['username'];
    }

    private function updateChats(): self
    {
        $chat = $this->chat['message']['chat'];
        $username = $chat['username'];
        $chat_id = $chat['id'];

        $updated = Chat::query()
            ->where([
                'username' => $username,
                'chat_id' => null,
                'user_id' => auth()->id(),
            ])
            ->update(['chat_id' => $chat_id]);

        if ($updated <= 0) {
            throw new NoChatsFound(__("No chat is found"));
        }


        return $this;
    }

    private function findMessageForUsernames(): self
    {
        $usernames = $this->getUsernames();
        $this->chat = null;

        foreach ($this->updates as $result) {
            if ($usernames->contains($result['message']['chat']['username'])) {
                $this->chat = $result;
                break;
            }
        }

        if ($this->chat == null) {
            throw new NoMessagesFoundForUsername(__('No message found from you!'));
        }

        return $this;
    }

    /**
     * @return mixed
     */
    private function getUsernames()
    {
        return $this->chats_without_chat_ids->pluck('username');
    }

    private function fetchMyNotLinkedChats(): self
    {
        $this->chats_without_chat_ids = Chat::query()
            ->myChats()
            ->where('chat_id', null)
            ->get();

        if ($this->chats_without_chat_ids->isEmpty()) {
            throw new NoFreeChatsForYourUsername(__("You don't have chats or all your chats are linked."));
        }

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
}
