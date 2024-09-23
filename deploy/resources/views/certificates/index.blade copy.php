<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Certificates') }}
        </h2>
    </x-slot>
    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-4">
                <a href="{{ route('certificates.create') }}" class="btn btn-primary">Create Certificate</a>
                <certificate-search :certificates="{{ $certificates->toJson() }}"></certificate-search>
            </div>

            @if ($message = Session::get('success'))
                <div class="alert alert-success mt-2">
                    {{ $message }}
                </div>
            @endif

            <div id="app">
                <certificate-list
                    :initial-certificates="{{ $certificates->toJson() }}"
                    edit-route-template="{{ route('certificates.edit', ':id') }}"
                    show-route-template="{{ route('certificates.show', ':id') }}"
                    delete-route-template="{{ route('certificates.destroy', ':id') }}"
                ></certificate-list>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Vue({
                el: '#app',
                components: {
                    'certificate-list': CertificateList
                }
            });
        });
    </script>
    @endpush
</x-app-layout>