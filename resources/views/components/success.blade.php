@if (session()->has('success'))
    <x-message
        class="fill-green"
    >
        <x-slot name="title">
            Success!
        </x-slot>
        {{ session('success') }}
    </x-message>
@endif
