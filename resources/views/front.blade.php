<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>
    <div class="py-5">
        <div class="max-w-7xl mx-auto py-5 sm:px-6 lg:px-8">
            @livewire('setting.line')
        </div>
    </div>
</x-app-layout>
