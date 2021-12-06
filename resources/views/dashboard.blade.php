<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Telegram') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <livewire:send-message-via-telegram-bot>
            </livewire:send-message-via-telegram-bot>

            <x-jet-section-border />
        </div>

        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <livewire:interact-with-telegram-bot>
            </livewire:interact-with-telegram-bot>

            <x-jet-section-border />
        </div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <livewire:add-chat>
            </livewire:add-chat>

            <x-jet-section-border />
        </div>

        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <livewire:chats-list>
            </livewire:chats-list>

            <x-jet-section-border />
        </div>
    </div>

</x-app-layout>
