<x-section>
    <x-slot name="title">
        {{ __('Linked Chats') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Any listed chat will receive the sent messages, be sure to remove any chat you don\'t want to receive messages.') }}
    </x-slot>

    <x-slot name="form">
        <x-flash/>

        <div class="col-span-6 grid grid-cols-6 gap-6 border-b-2 text-center">
            <div class="col-span-2">
                <strong>Chat ID</strong>
            </div>
            <div class="col-span-2">
                <strong>Username</strong>
            </div>
            <div class="col-span-2">
                <strong>Actions</strong>
            </div>
        </div>
        <div class="col-span-6 grid grid-cols-6 gap-6 text-center">
            @forelse($chats as $chat)
            <div class="col-span-2">
                {{ $chat->chat_id }}
            </div>
            <div class="col-span-2">
                <a href="https://t.me/{{$chat->username}}"
                   target="_blank"
                   class="underline text-blue-600 hover:text-blue-800 visited:text-purple-600">
                    {{ '@' . $chat->username }}
                </a>
            </div>
            <div class="col-span-2">
                <button
                    class="inline-flex items-center px-4 py-2 bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600 active:bg-red-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
                    wire:click="revoke({{$chat->id}})"
                    type="button"
                >
                    revoke
                </button>
            </div>
        @empty
            <div class="col-span-6 text-center">
                No chats available
            </div>
        @endforelse
        </div>
    </x-slot>
</x-section>
