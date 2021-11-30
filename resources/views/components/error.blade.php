@if (session()->has('error'))
    <x-message
        class="fill-red"
    >
        <x-slot name="title">
            Error!!!!1
        </x-slot>
        {{ session('error') }}
    </x-message>
@endif
