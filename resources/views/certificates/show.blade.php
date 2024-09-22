<x-app-layout>
    <div id="app">
        <certificate-viewer :initial-certificate="{{ json_encode($certificate) }}"></certificate-viewer>
    </div>

    @vite('resources/js/app.js')
</x-app-layout>