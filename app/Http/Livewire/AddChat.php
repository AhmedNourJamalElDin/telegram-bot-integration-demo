<?php

namespace App\Http\Livewire;

use App\Models\Chat;
use Livewire\Component;

class AddChat extends Component
{
    public ?string $username = null;

    protected array $rules = [
        'username' => ['required', 'string'],
    ];

    public function render()
    {
        return view('telegram.add-chat');
    }

    public function submit()
    {
        $this->validate();

        $data = [
            'username' => $this->username,
            'user_id' => auth()->id()
        ];

        Chat::firstOrCreate($data, $data);

        $this->refreshData();
        $this->emit('new-chat-created');
    }

    private function refreshData()
    {
        $this->username = null;
    }
}
