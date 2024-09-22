<x-app-layout>
    <div id="app">
        <certificate-creator :initial-certificate="{{ json_encode($certificate) }}"></certificate-creator>
    </div>

    @vite('resources/js/app.js')
</x-app-layout>