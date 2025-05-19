<x-app-layout>
    <div id="app">
        <certificate-viewer 
            :initial-certificate="{{ json_encode([
                'id' => $certificate->id,
                'title' => $certificate->title,
                'data' => $certificate->data,
                'quantity_hours' => $certificate->quantity_hours,
                'quantity_hours_online' => $certificate->quantity_hours_online,
                'quantity_hours_presential' => $certificate->quantity_hours_presential,
                'pages' => $certificate->pages,
                'orientation' => $certificate->orientation ?? 'landscape',
                'dimensions' => $certificate->dimensions ?? ['width' => 303.02, 'height' => 215.98],
                'categories' => $certificate->categories,
                'teachers' => $certificate->teachers
            ]) }}"
        ></certificate-viewer>
    </div>

    @vite('resources/js/app.js')
</x-app-layout>