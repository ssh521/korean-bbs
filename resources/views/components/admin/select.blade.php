@props([
    'label' => null,
    'srOnly' => false,
])

@php
    $selectId = $attributes->get('id');
    $labelClass = $srOnly ? 'sr-only' : 'mb-1.5 block text-xs font-medium text-gray-700';
@endphp

<div>
    @if($label)
        <label for="{{ $selectId }}" class="{{ $labelClass }}">{{ $label }}</label>
    @endif
    <select {{ $attributes->merge([
        'class' => 'block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500',
    ]) }}>
        {{ $slot }}
    </select>
</div>
