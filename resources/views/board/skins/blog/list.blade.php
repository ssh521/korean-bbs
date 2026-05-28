<div class="{{ $this->board->widthClass() }}" style="{{ $this->board->widthStyle() }}">
    {{-- 게시판 헤더 --}}
    <div class="mb-8 border-b border-gray-200 pb-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-semibold tracking-wide text-blue-600">블로그</p>
                <h2 class="mt-2 text-3xl font-bold text-gray-900">{{ $this->board->name }}</h2>
                @if($this->board->description)
                    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-gray-500">{{ $this->board->description }}</p>
                @endif
            </div>
            @if($this->canWrite())
                <a href="{{ route('bbs.posts.create', [$this->board->slug]) }}"
                   class="inline-flex items-center justify-center rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-700">
                    새 글 작성
                </a>
            @endif
        </div>
    </div>

    {{-- 공지글 --}}
    @if($notices->isNotEmpty())
        <div class="mb-8 rounded-lg border border-blue-100 bg-blue-50">
            @foreach($notices as $notice)
                <div class="flex items-center gap-3 border-b border-blue-100 px-4 py-3 last:border-0">
                    <span class="rounded bg-blue-600 px-2 py-0.5 text-xs font-medium text-white">공지</span>
                    @if($this->canRead())
                        <a href="{{ route('bbs.posts.show', [$this->board->slug, $notice->id]) }}"
                           class="min-w-0 flex-1 truncate text-sm font-semibold text-gray-900 hover:text-blue-700">
                            {{ $notice->title }}
                        </a>
                    @else
                        <span class="min-w-0 flex-1 truncate text-sm font-semibold text-gray-500">{{ $notice->title }}</span>
                    @endif
                    <span class="hidden text-xs text-gray-500 sm:inline">{{ $notice->created_at->format('Y.m.d') }}</span>
                </div>
            @endforeach
        </div>
    @endif

    {{-- 블로그 피드 --}}
    <div class="space-y-6">
        @forelse($posts as $post)
            @php $preview = trim(strip_tags($post->content ?? '')); @endphp

            @if($this->canRead())
                <a href="{{ route('bbs.posts.show', [$this->board->slug, $post->id]) }}"
                   class="group grid gap-5 rounded-lg border border-gray-200 bg-white p-4 transition hover:border-gray-300 hover:shadow-sm md:grid-cols-[220px_1fr]">
            @else
                <div class="grid gap-5 rounded-lg border border-gray-200 bg-white p-4 md:grid-cols-[220px_1fr]">
            @endif
                <div class="aspect-[4/3] overflow-hidden rounded-lg bg-gray-100">
                    @if($post->thumbnail_path)
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk(config('korean-bbs.upload.disk'))->url($post->thumbnail_path) }}"
                             alt="{{ $post->title }}"
                             class="h-full w-full object-cover transition duration-200 group-hover:scale-105">
                    @else
                        <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 px-6 text-center text-xs font-medium uppercase tracking-wide text-gray-400">
                            블로그
                        </div>
                    @endif
                </div>

                <article class="flex min-w-0 flex-col justify-center">
                    <div class="mb-2 flex flex-wrap items-center gap-2 text-xs text-gray-500">
                        <span class="font-medium text-gray-700">{{ $post->author_name }}</span>
                        <span aria-hidden="true">/</span>
                        <time datetime="{{ $post->created_at->toDateString() }}">{{ $post->created_at->format('Y.m.d') }}</time>
                        <span aria-hidden="true">/</span>
                        <span>조회 {{ number_format($post->view_count) }}</span>
                        @if($post->like_count > 0)
                            <span class="text-blue-600">추천 {{ $post->like_count }}</span>
                        @endif
                    </div>

                    <h3 class="text-xl font-bold leading-snug text-gray-900 group-hover:text-blue-700">
                        @if($post->is_secret)
                            <span class="mr-1 text-sm font-medium text-gray-400">[비밀글]</span>
                        @endif
                        {{ $post->title }}
                    </h3>

                    @if($preview)
                        <p class="mt-3 line-clamp-2 text-sm leading-relaxed text-gray-600">
                            {{ Str::limit($preview, 180) }}
                        </p>
                    @endif

                    <div class="mt-4 flex flex-wrap items-center gap-3 text-xs text-gray-500">
                        @if($post->all_comments_count > 0)
                            <span>댓글 {{ $post->all_comments_count }}</span>
                        @endif
                        @if($post->files->isNotEmpty() ?? false)
                            <span>첨부파일</span>
                        @endif
                    </div>
                </article>
            @if($this->canRead())
                </a>
            @else
                </div>
            @endif
        @empty
            <div class="rounded-lg border border-dashed border-gray-300 bg-white py-16 text-center text-sm text-gray-400">
                첫 번째 블로그 글을 작성해보세요.
            </div>
        @endforelse
    </div>

    {{-- 검색 & 페이지네이션 --}}
    <div class="mt-8 flex flex-col items-center justify-between gap-4 border-t border-gray-200 pt-5 md:flex-row">
        <div class="w-full md:w-auto">
            <x-korean-bbs::pagination :paginator="$posts" />
        </div>

        <form wire:submit.prevent class="flex w-full gap-2 sm:w-auto">
            <select wire:model.live="searchType" class="rounded border border-gray-300 px-2 py-1 text-sm">
                <option value="title">제목</option>
                <option value="content">내용</option>
                <option value="author">작성자</option>
            </select>
            <input type="text" wire:model.live.debounce.400ms="search"
                   placeholder="검색어 입력..."
                   class="min-w-0 flex-1 rounded border border-gray-300 px-3 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400 sm:w-48">
        </form>
    </div>
</div>
