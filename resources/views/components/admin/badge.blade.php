@props([
    'variant' => 'neutral',
])

@php
    $variantClasses = [
        'neutral' => 'bg-gray-100 text-gray-700',
        'success' => 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20',
        'danger' => 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20',
        'warning' => 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20',
        'info' => 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20',
    ][$variant] ?? 'bg-gray-100 text-gray-700';
@endphp

<span {{ $attributes->merge([
    'class' => "inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {$variantClasses}",
]) }}>
    {{ $slot }}
</span>
