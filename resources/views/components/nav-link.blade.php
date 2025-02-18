@props(['active'])

@php
$classes = ($active ?? false)
? 'border border-b-4 border-purple-900 hover:bg-purple-300 text-stone-900 bg-purple-400 font-bold py-3 ps-2 pe-4 rounded-xl mt-3 flex flex-col justify-end gap-2 items-center w-14 h-14 text-xs pb-1'
: 'border border-b-4 border-purple-700 hover:bg-purple-300 text-stone-900 bg-purple-400 font-bold px-3 mt-3 rounded-xl flex flex-col justify-end gap-2 items-center w-14 h-14 text-xs pb-1';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
