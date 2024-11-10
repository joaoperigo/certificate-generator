@props(['active'])

@php
$classes = ($active ?? false)
            ? 'border border-b-4 border-stone-600 hover:bg-purple-300 text-stone-900 bg-purple-400 font-bold py-3 ps-2 pe-4 rounded-xl mt-3 flex justify-center gap-2 items-center h-[44px]'
            : 'border border-b-4 border-stone-600 hover:bg-purple-300 text-stone-900 bg-purple-400 font-bold px-3 mt-3 rounded-xl flex justify-center gap-2 items-center h-[44px]';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
