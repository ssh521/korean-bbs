<div>
    <h2 class="text-2xl font-bold text-gray-800 mb-6">게시판</h2>

    @if($groups->isNotEmpty())
        @foreach($groups as $group)
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-3 pb-2 border-b-2 border-blue-500">
                    {{ $group->name }}
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                    @foreach($group->boards as $board)
                        <a href="{{ route('bbs.posts.index', $board->slug) }}"
                           class="bg-white border border-gray-200 rounded-lg p-4 hover:border-blue-400 hover:shadow-md transition group">
                            <div class="font-medium text-gray-800 group-hover:text-blue-600">{{ $board->name }}</div>
                            @if($board->description)
                                <div class="text-xs text-gray-500 mt-1 truncate">{{ $board->description }}</div>
                            @endif
                            <div class="text-xs text-gray-400 mt-2">
                                {{ $board->skin }}
                                · {{ number_format($board->posts_count ?? 0) }}개
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    @endif

    @if($noGroupBoards->isNotEmpty())
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
            @foreach($noGroupBoards as $board)
                <a href="{{ route('bbs.posts.index', $board->slug) }}"
                   class="bg-white border border-gray-200 rounded-lg p-4 hover:border-blue-400 hover:shadow-md transition group">
                    <div class="font-medium text-gray-800 group-hover:text-blue-600">{{ $board->name }}</div>
                    @if($board->description)
                        <div class="text-xs text-gray-500 mt-1 truncate">{{ $board->description }}</div>
                    @endif
                </a>
            @endforeach
        </div>
    @endif

    @if($groups->isEmpty() && $noGroupBoards->isEmpty())
        <div class="text-center text-gray-500 py-16">아직 게시판이 없습니다.</div>
    @endif
</div>
