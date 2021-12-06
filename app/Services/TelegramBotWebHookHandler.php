<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Collection;

class TelegramBotWebHookHandler
{
    private Collection $updates;
    private Collection $exceptions;

    public function __construct()
    {
        $this->exceptions = collect();
    }

    public function handle($updates)
    {
        $this->begin()
            ->setUpdates($updates)
            ->byUsername()
            ->byToken()
            ->throwFirstException();
    }

    private function throwFirstException()
    {
        if ($this->exceptions->isEmpty()) {
            return;
        }

        throw $this->exceptions->first();
    }

    private function byToken(): self
    {
        $handler = new TokenTelegramBotWebHookHandler();

        try {
            $handler->handle($this->updates);
        } catch (Exception $exception) {
            $this->exceptions->add($exception);
        }

        return $this;
    }

    private function byUsername(): self
    {
        $handler = new UsernameTelegramBotWebHookHandler();

        try {
            $handler->handle($this->updates);
        } catch (Exception $e) {
            $this->exceptions->add($e);
        }

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
