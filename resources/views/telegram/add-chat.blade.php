<x-jet-form-section submit="submit">
    <x-slot name="title">
        {{ __('Add Chat') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Add new chat and make sure to interact with the bot!') }}
    </x-slot>

    <x-slot name="form">
        <x-flash/>

        <div class="col-span-6">
            <x-jet-label for="username" value="{{ __('Username') }}"/>
            <x-jet-input id="username" name="username" type="text" class="mt-1 block w-full" wire:model.defer="username"/>
            <x-jet-input-error for="username" class="mt-2"/>
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-jet-button class="mr-3">
            {{ __('Add Chat') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
