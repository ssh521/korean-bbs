@props([
    'title' => '데이터가 없습니다.',
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'rounded-xl border border-dashed border-gray-300 bg-white px-6 py-12 text-center']) }}>
    <h3 class="text-sm font-semibold text-gray-900">{{ $title }}</h3>
    @if($description)
        <p class="mt-2 text-sm text-gray-500">{{ $description }}</p>
    @endif
    @if(isset($actions))
        <div class="mt-5 flex justify-center">
            {{ $actions }}
        </div>
    @endif
</div>
