@props(['messages' => null, 'for' => null])

@php
    $messages = $messages ?? ($for ? $errors->get($for) : null);
@endphp

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-sm text-red-600 dark:text-red-400 space-y-1']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif