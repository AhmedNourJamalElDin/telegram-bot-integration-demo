<x-section>
    <x-slot name="title">
        {{ __('Interact With The Bot!') }}
    </x-slot>

    <x-slot name="description">
        {{ __('In order to listen for messages from the bot, you first need to interact with the bot.') }}
    </x-slot>

    <x-slot name="form">
        <x-flash/>

        <div class="col-span-6 text-red-700 text-center">
            {{ __('Never share the interaction link with anyone. Otherwise, he will receive messages on your behalf.') }}
            <br>
            {{ __('You can still revoke the chats using the Linked Chats card below!') }}
        </div>

        <div class="col-span-2">
        </div>
        <div class="col-span-1">
            <a href="https://telegram.me/anjd_tests_bot?start={{ $token }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
               wire:loading.attr="disabled"
               target="_blank">
                {{ __('Interact') }}
            </a>
        </div>

        <div class="col-span-1">
            <button wire:click="interacted"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                {{ __('Interacted') }}
            </button>
        </div>

        <div class="col-span-2">
        </div>
    </x-slot>
</x-section>
