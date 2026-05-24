<div class="{{ $this->board->widthClass() }}" style="{{ $this->board->widthStyle() }}">
    {{-- 게시판 헤더 --}}
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">{{ $this->board->name }}</h2>
            @if($this->board->description)
                <p class="text-sm text-gray-500 mt-1">{{ $this->board->description }}</p>
            @endif
        </div>
        <a href="{{ route('bbs.posts.create', $this->board->slug) }}"
           class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            글쓰기
        </a>
    </div>

    {{-- 공지글 --}}
    @if($notices->isNotEmpty())
        <div class="bg-white border border-gray-200 rounded-lg mb-1 overflow-hidden">
            @foreach($notices as $notice)
                <div class="flex items-center gap-3 px-4 py-3 border-b border-gray-100 last:border-0 bg-yellow-50">
                    <span class="text-xs bg-red-500 text-white px-2 py-0.5 rounded font-medium flex-shrink-0">공지</span>
                    <a href="{{ route('bbs.posts.show', [$this->board->slug, $notice->id]) }}"
                       class="flex-1 text-sm font-medium text-gray-800 hover:text-blue-600 truncate">
                        {{ $notice->title }}
                    </a>
                    <span class="text-xs text-gray-400 flex-shrink-0">{{ $notice->created_at->format('Y.m.d') }}</span>
                </div>
            @endforeach
        </div>
    @endif

    {{-- 게시글 목록 --}}
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        {{-- 목록 헤더 --}}
        <div class="hidden md:grid grid-cols-12 gap-2 px-4 py-2 bg-gray-50 border-b border-gray-200 text-xs text-gray-500 font-medium">
            <div class="col-span-1 text-center">번호</div>
            <div class="col-span-6">제목</div>
            <div class="col-span-2 text-center">작성자</div>
            <div class="col-span-1 text-center">조회</div>
            <div class="col-span-1 text-center">추천</div>
            <div class="col-span-1 text-center">날짜</div>
        </div>

        @forelse($posts as $post)
            <div class="grid grid-cols-12 gap-2 px-4 py-3 border-b border-gray-100 last:border-0 hover:bg-gray-50 transition items-center">
                <div class="hidden md:block col-span-1 text-center text-xs text-gray-400">{{ $post->id }}</div>
                <div class="col-span-9 md:col-span-6">
                    <a href="{{ route('bbs.posts.show', [$this->board->slug, $post->id]) }}"
                       class="text-sm text-gray-800 hover:text-blue-600 font-medium">
                        @if($post->is_secret)
                            <span class="text-gray-400">[비밀글]</span>
                        @endif
                        {{ $post->title }}
                        @if($post->all_comments_count > 0)
                            <span class="text-blue-500 text-xs ml-1">[{{ $post->all_comments_count }}]</span>
                        @endif
                    </a>
                    @if($post->files->isNotEmpty() ?? false)
                        <span class="ml-1 text-gray-400">📎</span>
                    @endif
                </div>
                <div class="hidden md:block col-span-2 text-center text-xs text-gray-600">{{ $post->author_name }}</div>
                <div class="hidden md:block col-span-1 text-center text-xs text-gray-500">{{ number_format($post->view_count) }}</div>
                <div class="hidden md:block col-span-1 text-center text-xs text-blue-500">{{ $post->like_count > 0 ? '+' . $post->like_count : '' }}</div>
                <div class="col-span-3 md:col-span-1 text-right md:text-center text-xs text-gray-400">
                    {{ $post->created_at->diffForHumans() }}
                </div>
            </div>
        @empty
            <div class="text-center text-gray-400 py-16 text-sm">
                첫 번째 게시글을 작성해보세요.
            </div>
        @endforelse
    </div>

    {{-- 검색 & 페이지네이션 --}}
    <div class="mt-4 flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
            {{ $posts->links() }}
        </div>

        <form wire:submit.prevent class="flex gap-2">
            <select wire:model.live="searchType" class="text-sm border border-gray-300 rounded px-2 py-1">
                <option value="title">제목</option>
                <option value="content">내용</option>
                <option value="author">작성자</option>
            </select>
            <input type="text" wire:model.live.debounce.400ms="search"
                   placeholder="검색어 입력..."
                   class="text-sm border border-gray-300 rounded px-3 py-1 w-40 focus:outline-none focus:ring-1 focus:ring-blue-400">
        </form>
    </div>
</div>
