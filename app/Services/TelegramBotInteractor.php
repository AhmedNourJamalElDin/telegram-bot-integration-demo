<?php

namespace App\Services;

use App\Models\Chat;
use Error;
use Exception;
use Illuminate\Support\Collection;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramBotInteractor
{
    private $updates;
    private Collection $exceptions;
    private ?Chat $chat = null;

    public function __construct()
    {
        $this->exceptions = collect();
    }

    public function interacted(): Chat
    {
        $this->fetchUpdates()
            ->interactedByUsername()
            ->interactedByToken()
            ->throwFirstExceptionIfNoChatCreated();

        return $this->chat;
    }

    private function throwFirstExceptionIfNoChatCreated(): self
    {
        if ($this->chat != null) {
            return $this;
        }

        if ($this->exceptions->isEmpty()) {
            return $this;
        }

        throw $this->exceptions->first();
    }

    private function interactedByToken(): self
    {
        if ($this->chat != null) {
            return $this;
        }

        try {
            $token_interactor = new TokenTelegramBotInteractor();

            $this->chat = $token_interactor->interacted($this->updates);
        } catch (Exception $exception) {
            $this->exceptions->add($exception);
        }

        return $this;
    }

    private function interactedByUsername(): self
    {
        try {
            $username_interactor = new UsernameTelegramBotInteractor();

            $this->chat = $username_interactor->interacted($this->updates);

        } catch (Error | Exception $exception) {
            $this->exceptions->add($exception);
        }


        return $this;
    }

    private function fetchUpdates(): self
    {
        $this->updates = Telegram::getUpdates();

        return $this;
    }
}
