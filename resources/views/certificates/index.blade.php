<x-app-layout>

    <div class="bg-slate-200">
        <div class="max-w-7xl mx-auto">
            <div id="app">
                <div class="flex justify-between mb-4 xl:container fex-row h-screen">
                    <div class="w-full px-40 mt-[100px]">
                        <certificate-search 
                            :certificates="{{ $certificates->toJson() }}"
                        ></certificate-search>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // document.addEventListener('DOMContentLoaded', function() {
        //     new Vue({
        //         el: '#app',
        //         components: {
        //             'certificate-search': CertificateSearch,
        //         }
        //     });
        // });
    </script>
    @endpush
</x-app-layout>