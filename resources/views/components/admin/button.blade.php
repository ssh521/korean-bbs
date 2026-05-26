@props([
    'variant' => 'secondary',
    'size' => 'md',
    'full' => false,
])

@php
    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2 text-sm',
    ][$size] ?? 'px-4 py-2 text-sm';

    $variantClasses = [
        'primary' => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500',
        'secondary' => 'border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:ring-blue-500',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
        'danger-soft' => 'border border-red-300 bg-white text-red-600 hover:bg-red-50 focus:ring-red-500',
    ][$variant] ?? 'border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:ring-blue-500';

    $widthClasses = $full ? 'w-full' : '';
@endphp

<button {{ $attributes->merge([
    'type' => 'button',
    'class' => "inline-flex items-center justify-center gap-2 rounded-lg font-medium transition focus:outline-none focus:ring-2 focus:ring-offset-2 {$sizeClasses} {$variantClasses} {$widthClasses}",
]) }}>
    {{ $slot }}
</button>
