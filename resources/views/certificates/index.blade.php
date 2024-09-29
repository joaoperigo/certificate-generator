<x-app-layout>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div id="app">
                <div class="flex justify-between items-center mb-4">
                    <a href="{{ route('certificates.create') }}" class="btn btn-primary">Create Certificate</a>
                    <certificate-search 
                        :certificates="{{ $certificates->toJson() }}"
                    ></certificate-search>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Vue({
                el: '#app',
                components: {
                    'certificate-search': CertificateSearch
                }
            });
        });
    </script>
    @endpush
</x-app-layout>