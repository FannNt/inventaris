<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Items') }}
        </h2>
    </x-slot>
    <div class="py-4">
        @livewire("items")
    </div>
</x-app-layout>

