@props([
    'paginator',
    'variant' => 'default',
])

@php
    use Illuminate\Pagination\UrlWindow;

    $window = $paginator->hasPages() ? UrlWindow::make($paginator) : [];
    $elements = $paginator->hasPages()
        ? array_filter([
            $window['first'] ?? null,
            is_array($window['slider'] ?? null) ? '...' : null,
            $window['slider'] ?? null,
            is_array($window['last'] ?? null) ? '...' : null,
            $window['last'] ?? null,
        ])
        : [];
    $isAdmin = $variant === 'admin';
    $buttonBase = 'inline-flex h-9 min-w-9 items-center justify-center border px-3 text-sm font-medium transition';
    $normalButton = $isAdmin
        ? 'border-gray-200 bg-white text-gray-600 hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700'
        : 'border-gray-200 bg-white text-gray-600 hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700';
    $activeButton = 'border-blue-600 bg-blue-600 text-white shadow-sm';
    $disabledButton = 'cursor-not-allowed border-gray-200 bg-gray-50 text-gray-300';
    $pageUrl = fn (?string $url) => $url && ! preg_match('/^(https?:\/\/|\/|\?|#)/', $url)
        ? url($url)
        : $url;
@endphp

@if($paginator->hasPages())
    <nav class="flex w-full flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
         role="navigation"
         aria-label="페이지네이션">
        <p class="text-xs text-gray-500">
            총 <span class="font-medium text-gray-700">{{ number_format($paginator->total()) }}</span>건
            <span class="mx-1 text-gray-300">/</span>
            {{ number_format($paginator->firstItem()) }}-{{ number_format($paginator->lastItem()) }}
        </p>

        <div class="flex items-center justify-center gap-1">
            @if($paginator->onFirstPage())
                <span class="{{ $buttonBase }} {{ $disabledButton }} rounded-l-lg" aria-disabled="true" aria-label="이전 페이지">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </span>
            @else
                <a href="{{ $pageUrl($paginator->previousPageUrl()) }}"
                   class="{{ $buttonBase }} {{ $normalButton }} rounded-l-lg"
                   rel="prev"
                   aria-label="이전 페이지">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
            @endif

            <div class="hidden items-center gap-1 sm:flex">
                @foreach($elements as $element)
                    @if(is_string($element))
                        <span class="{{ $buttonBase }} {{ $disabledButton }}" aria-disabled="true">{{ $element }}</span>
                    @endif

                    @if(is_array($element))
                        @foreach($element as $page => $url)
                            @if($page == $paginator->currentPage())
                                <span class="{{ $buttonBase }} {{ $activeButton }}" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $pageUrl($url) }}"
                                   class="{{ $buttonBase }} {{ $normalButton }}"
                                   aria-label="{{ $page }} 페이지로 이동">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            <span class="inline-flex h-9 min-w-20 items-center justify-center border border-gray-200 bg-white px-3 text-sm font-medium text-gray-600 sm:hidden">
                {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
            </span>

            @if($paginator->hasMorePages())
                <a href="{{ $pageUrl($paginator->nextPageUrl()) }}"
                   class="{{ $buttonBase }} {{ $normalButton }} rounded-r-lg"
                   rel="next"
                   aria-label="다음 페이지">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @else
                <span class="{{ $buttonBase }} {{ $disabledButton }} rounded-r-lg" aria-disabled="true" aria-label="다음 페이지">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            @endif
        </div>
    </nav>
@endif
