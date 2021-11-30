<?php

namespace App\Http\Livewire;

use App\Models\Chat;
use Illuminate\Support\Collection;
use Livewire\Component;

class ChatsList extends Component
{
    public $listeners = [
        'new-chat-linked' => 'loadChats',
    ];

    public Collection $chats;

    public function mount()
    {
        $this->loadChats();
    }

    public function loadChats()
    {
        $this->chats = Chat::query()
            ->myChats()
            ->get();
    }

    public function render()
    {
        return view('telegram.chats-list');
    }

    public function revoke($chat_id)
    {
        Chat::query()
            ->where('id', $chat_id)
            ->myChats()
            ->delete();

        $this->loadChats();
    }
}
