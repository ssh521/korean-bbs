<div>
    <h2 class="text-2xl font-bold text-gray-800 mb-6">대시보드</h2>

    {{-- 통계 카드 --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="text-sm text-gray-500 mb-1">전체 게시판</div>
            <div class="text-3xl font-bold text-blue-600">{{ number_format($stats['boards']) }}</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="text-sm text-gray-500 mb-1">전체 게시글</div>
            <div class="text-3xl font-bold text-green-600">{{ number_format($stats['posts']) }}</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="text-sm text-gray-500 mb-1">전체 댓글</div>
            <div class="text-3xl font-bold text-purple-600">{{ number_format($stats['comments']) }}</div>
        </div>
    </div>

    {{-- 최근 게시글 --}}
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-700">최근 게시글</h3>
            <a href="{{ route('bbs.admin.posts.index') }}" class="text-xs text-blue-600 hover:underline">전체보기</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($stats['recent_posts'] as $post)
                <div class="px-6 py-3 flex items-center gap-4">
                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded flex-shrink-0">
                        {{ $post->board->name ?? '-' }}
                    </span>
                    <a href="{{ route('bbs.posts.show', [$post->board->slug ?? '#', $post->id]) }}"
                       class="flex-1 text-sm text-gray-700 hover:text-blue-600 truncate">
                        {{ $post->title }}
                    </a>
                    <span class="text-xs text-gray-400 flex-shrink-0">
                        {{ $post->created_at->format('m.d H:i') }}
                    </span>
                </div>
            @empty
                <div class="px-6 py-8 text-center text-sm text-gray-400">게시글이 없습니다.</div>
            @endforelse
        </div>
    </div>
</div>
