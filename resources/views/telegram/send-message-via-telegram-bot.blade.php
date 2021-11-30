<x-jet-form-section submit="submit" wire:init="load">
    <x-slot name="title">
        {{ __('Send Message') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Send a message to a user via your telegram bot but the user should have interacted with the bot in the first place.') }}
    </x-slot>

    <x-slot name="form">
        <x-flash/>

        <div class="col-span-6">
            <x-jet-label for="user_id" value="{{ __('User') }}"/>
            <select name="user_id" id="user_id" wire:model.defer="user_id"
                    class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full">
                @if($is_loaded)
                    <option value="{{null}}">User</option>
                    @foreach($users as $value => $text)
                        <option value="{{$value}}">{{$text}}</option>
                    @endforeach
                @else
                    Loading
                @endif
            </select>
            <x-jet-input-error for="user_id" class="mt-2"/>
        </div>

        <div class="col-span-6">
            <x-jet-label for="message" value="{{ __('Message') }}"/>
            <x-jet-input id="message" name="message" type="text" class="mt-1 block w-full" wire:model.defer="message"/>
            <x-jet-input-error for="message" class="mt-2"/>
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            {{ __('Sent.') }}
        </x-jet-action-message>

        <x-jet-button class="mr-3" wire:click.prevent="submitNotification" wire:loading.attr="disabled">
            {{ __('Send As Queued Notification') }}
        </x-jet-button>

        <x-jet-button class="mr-3" wire:loading.attr="disabled">
            {{ __('Send Now') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
