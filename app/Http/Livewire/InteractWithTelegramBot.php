<?php

namespace App\Http\Livewire;

use App\Services\TelegramBotInteractor;
use Exception;
use Livewire\Component;

class InteractWithTelegramBot extends Component
{
    const id = 'TELEGRAM_START_TOKEN';
    public ?string $token = null;

    public function mount()
    {
        $this->token = my_telegram_token();
    }

    public function render()
    {
        return view('telegram.interact-with-telegram-bot');
    }

    public function interacted()
    {
        $interactor = new TelegramBotInteractor();

        try {
            $chat = $interactor->interacted();
            $username = $chat->username;

            session()->flash('success', __(':username is linked now', ['username' => $username]));
            $this->emit('new-chat-linked');
        } catch (Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}
