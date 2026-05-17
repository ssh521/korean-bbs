<div>
    {{-- 게시판 헤더 --}}
    <div class="flex items-center justify-between mb-6">
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
        <div class="mb-4 space-y-1">
            @foreach($notices as $notice)
                <div class="flex items-center gap-3 bg-yellow-50 border border-yellow-200 rounded-lg px-4 py-2">
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

    {{-- 카드 피드 목록 --}}
    <div class="space-y-3">
        @forelse($posts as $post)
            <a href="{{ route('bbs.posts.show', [$this->board->slug, $post->id]) }}"
               class="flex gap-4 bg-white border border-gray-200 rounded-xl p-4 hover:border-blue-300 hover:shadow-sm transition group">

                {{-- 썸네일 (있을 때만) --}}
                @if($post->thumbnail_path)
                    <div class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden bg-gray-100">
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk(config('korean-bbs.upload.disk'))->url($post->thumbnail_path) }}"
                             alt="{{ $post->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                    </div>
                @endif

                {{-- 본문 --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <div class="text-sm font-semibold text-gray-800 group-hover:text-blue-600 leading-snug truncate">
                            @if($post->is_secret)
                                <span class="text-gray-400 font-normal">[비밀글]</span>
                            @endif
                            {{ $post->title }}
                            @if($post->all_comments_count > 0)
                                <span class="text-blue-500 font-normal text-xs ml-1">[{{ $post->all_comments_count }}]</span>
                            @endif
                            @if($post->files->isNotEmpty() ?? false)
                                <span class="text-gray-400 text-xs ml-0.5">📎</span>
                            @endif
                        </div>
                        <span class="text-xs text-gray-400 flex-shrink-0">{{ $post->created_at->format('m.d') }}</span>
                    </div>

                    {{-- 내용 미리보기 --}}
                    @php $preview = strip_tags($post->content ?? ''); @endphp
                    @if($preview)
                        <p class="text-xs text-gray-500 mt-1 line-clamp-2 leading-relaxed">
                            {{ Str::limit($preview, 120) }}
                        </p>
                    @endif

                    {{-- 메타 --}}
                    <div class="flex items-center gap-3 mt-2 text-xs text-gray-400">
                        <span>{{ $post->author_name }}</span>
                        <span>조회 {{ number_format($post->view_count) }}</span>
                        @if($post->like_count > 0)
                            <span class="text-blue-400">추천 {{ $post->like_count }}</span>
                        @endif
                    </div>
                </div>
            </a>
        @empty
            <div class="text-center text-gray-400 py-16 text-sm bg-white border border-gray-200 rounded-xl">
                첫 번째 게시글을 작성해보세요.
            </div>
        @endforelse
    </div>

    {{-- 검색 & 페이지네이션 --}}
    <div class="mt-6 flex flex-col md:flex-row items-center justify-between gap-4">
        <div>{{ $posts->links() }}</div>
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
