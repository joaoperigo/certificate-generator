<x-app-layout>
    <div id="app">

    <certificate-creator 
            :initial-certificate="{{ json_encode([
                'id' => $certificate->id,
                'title' => $certificate->title,
                'data' => $certificate->data,
                'quantity_hours' => $certificate->quantity_hours,
                'pages' => $certificate->pages,
                'categories' => $certificate->categories,
                'teachers' => $certificate->teachers
            ]) }}"
        ></certificate-creator>
    </div>

    @vite('resources/js/app.js')
</x-app-layout>