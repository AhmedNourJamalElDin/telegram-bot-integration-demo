<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Notifications\SendMessageViaTelegramBotNotification;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use Telegram\Bot\Laravel\Facades\Telegram;

class SendMessageViaTelegramBot extends Component
{
    public ?string $message = null;
    public ?string $user_id = null;

    public Collection $users;
    public $is_loaded = false;

    protected array $rules = [
        'message' => ['required', 'string'],
        'user_id' => ['required', 'string'],
    ];

    protected $validationAttributes = [
        'user_id' => 'user',
    ];

    public function mount()
    {
        $this->users = collect();
    }

    public function load()
    {
        $this->users = User::query()->pluck('email', 'id');
        $this->is_loaded = true;
    }

    public function submit()
    {
        $this->validate();

        try {
            User::query()
                ->with('chats', fn ($q) => $q->select('user_id', 'chat_id'))
                ->findOrFail($this->user_id)
                ->chats
                ->each(fn ($chat) => Telegram::sendMessage([
                    'chat_id' => $chat->chat_id,
                    'text' => $this->message,
                ]));
            session()->flash('success', __('Your message was sent successfully'));
        } catch (Exception $exception) {
            session()->flash('error', $exception->getMessage());
        }
    }

    public function submitNotification()
    {
        $this->validate();

        try {
            $chats = User::query()
                ->with('chats')
                ->findOrFail($this->user_id)
                ->chats;

            Notification::send($chats, new SendMessageViaTelegramBotNotification($this->message));

            session()->flash('success', __('Your message was scheduled successfully'));
        } catch (Exception $exception) {
            session()->flash('error', $exception->getMessage());
        }
    }

    public function render()
    {
        return view('telegram.send-message-via-telegram-bot');
    }
}
