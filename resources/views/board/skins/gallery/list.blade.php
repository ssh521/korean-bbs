<div class="{{ $this->board->widthClass() }}" style="{{ $this->board->widthStyle() }}">
    {{-- 게시판 헤더 --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">{{ $this->board->name }}</h2>
            @if($this->board->description)
                <p class="text-sm text-gray-500 mt-1">{{ $this->board->description }}</p>
            @endif
        </div>
        @if($this->canWrite())
            <a href="{{ route('bbs.posts.create', [$this->board->slug]) }}"
               class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                글쓰기
            </a>
        @endif
    </div>

    {{-- 공지글 --}}
    @if($notices->isNotEmpty())
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-6">
            @foreach($notices as $notice)
                <div class="flex items-center gap-2 py-1">
                    <span class="text-xs bg-red-500 text-white px-2 py-0.5 rounded">공지</span>
                    @if($this->canRead())
                        <a href="{{ route('bbs.posts.show', [$this->board->slug, $notice->id]) }}"
                           class="text-sm text-gray-700 hover:text-blue-600">{{ $notice->title }}</a>
                    @else
                        <span class="text-sm text-gray-500">{{ $notice->title }}</span>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    {{-- 갤러리 그리드 --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @forelse($posts as $post)
            @if($this->canRead())
                <a href="{{ route('bbs.posts.show', [$this->board->slug, $post->id]) }}"
                   class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md hover:border-blue-300 transition group">
            @else
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden group">
            @endif
                {{-- 썸네일 --}}
                <div class="aspect-square bg-gray-100 overflow-hidden">
                    @if($post->thumbnail_path)
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk(config('korean-bbs.upload.disk'))->url($post->thumbnail_path) }}"
                             alt="{{ $post->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                </div>

                <div class="p-3">
                    <div class="text-sm font-medium text-gray-800 truncate group-hover:text-blue-600">
                        {{ $post->title }}
                    </div>
                    <div class="flex items-center justify-between mt-2 text-xs text-gray-400">
                        <span>{{ $post->author_name }}</span>
                        <span>조회 {{ number_format($post->view_count) }}</span>
                    </div>
                    @if($post->all_comments_count > 0)
                        <div class="text-xs text-blue-500 mt-1">댓글 {{ $post->all_comments_count }}</div>
                    @endif
                </div>
            @if($this->canRead())
                </a>
            @else
                </div>
            @endif
        @empty
            <div class="col-span-full text-center text-gray-400 py-16 text-sm">
                첫 번째 게시글을 작성해보세요.
            </div>
        @endforelse
    </div>

    {{-- 검색 & 페이지네이션 --}}
    <div class="mt-6 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="w-full md:w-auto">
            <x-korean-bbs::pagination :paginator="$posts" />
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
