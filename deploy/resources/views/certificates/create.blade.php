<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Certificate') }}
        </h2>
    </x-slot> --}}

    <div id="app">
        <certificate-creator></certificate-creator>
    </div>

    @vite('resources/js/app.js')
</x-app-layout>