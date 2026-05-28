<div class="{{ $this->board->widthClass() }}" style="{{ $this->board->widthStyle() }}">
    {{-- 비밀글 잠금 화면 --}}
    @if(!$secretUnlocked)
        <div class="rounded-lg border border-gray-200 bg-white p-10 text-center">
            <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-sm font-semibold text-gray-500">잠금</div>
            <h3 class="text-lg font-semibold text-gray-800">비밀글입니다</h3>
            <p class="mt-2 text-sm text-gray-500">작성자만 볼 수 있는 비밀글입니다.</p>
            <div class="mt-6 flex justify-center gap-2">
                <input type="password" wire:model="secretPassword"
                       wire:keydown.enter="unlockSecret"
                       placeholder="비밀번호"
                       class="rounded-lg border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400">
                <button wire:click="unlockSecret"
                        class="rounded-lg bg-gray-900 px-4 py-2 text-sm text-white hover:bg-gray-700">
                    확인
                </button>
            </div>
            @error('secretPassword')
                <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
    @else

    {{-- 게시글 본문 --}}
    <article class="overflow-hidden rounded-lg border border-gray-200 bg-white">
        <header class="border-b border-gray-100 px-5 py-8 md:px-8">
            <div class="mb-4 flex flex-wrap items-center gap-2 text-xs text-gray-500">
                @if($this->post->is_notice)
                    <span class="rounded bg-blue-600 px-2 py-0.5 font-medium text-white">공지</span>
                @endif
                <span>{{ $this->board->name }}</span>
                <span aria-hidden="true">/</span>
                <time datetime="{{ $this->post->created_at->toDateString() }}">{{ $this->post->created_at->format('Y.m.d H:i') }}</time>
            </div>
            <h1 class="max-w-3xl text-3xl font-bold leading-tight text-gray-950">{{ $this->post->title }}</h1>
            <div class="mt-5 flex flex-wrap items-center gap-4 text-sm text-gray-500">
                <span class="font-medium text-gray-800">{{ $this->post->author_name }}</span>
                <span>조회 {{ number_format($this->post->view_count) }}</span>
                @if($this->post->like_count > 0)
                    <span class="text-blue-600">추천 {{ $this->post->like_count }}</span>
                @endif
            </div>
        </header>

        {{-- 첨부파일 --}}
        @if($this->post->files->isNotEmpty() && $this->canDownloadFile())
            <div class="border-b border-gray-100 bg-gray-50 px-5 py-4 md:px-8">
                <div class="mb-2 text-xs font-medium text-gray-500">첨부파일</div>
                <div class="flex flex-wrap gap-2">
                    @foreach($this->post->files as $file)
                        <a href="{{ route('bbs.file.download', $file->id) }}"
                           class="rounded border border-gray-200 bg-white px-3 py-1.5 text-xs text-gray-700 transition hover:border-blue-300 hover:text-blue-700">
                            {{ $file->original_name }}
                            <span class="text-gray-400">({{ $file->humanSize() }})</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="prose max-w-none px-5 py-8 text-gray-800 md:px-8">
            @include($contentView ?? 'korean-bbs::editors.content', ['content' => $this->post->content])
        </div>

        @php $images = $this->post->files->where('is_image', true); @endphp
        @if($images->isNotEmpty() && $this->canDownloadFile())
            <div class="grid gap-3 px-5 pb-8 md:grid-cols-2 md:px-8">
                @foreach($images as $img)
                    <img src="{{ $img->url() }}" alt="{{ $img->original_name }}"
                         class="max-h-80 w-full rounded-lg border border-gray-200 object-cover cursor-pointer hover:opacity-90"
                         onclick="window.open(this.src)">
                @endforeach
            </div>
        @endif

        @if($this->canLike())
            <div class="flex flex-wrap items-center justify-center gap-3 border-t border-gray-100 px-5 py-5">
                <button wire:click="toggleLike('like')"
                        class="rounded-full border border-blue-300 px-5 py-2 text-sm font-medium text-blue-600 transition hover:bg-blue-50">
                    추천 {{ $this->post->like_count }}
                </button>
                <button wire:click="toggleLike('dislike')"
                        class="rounded-full border border-red-200 px-5 py-2 text-sm font-medium text-red-500 transition hover:bg-red-50">
                    비추천 {{ $this->post->dislike_count }}
                </button>
            </div>
        @endif

        <footer class="flex items-center justify-between border-t border-gray-100 bg-gray-50 px-5 py-4 md:px-8">
            <a href="{{ route('bbs.posts.index', $this->board->slug) }}"
               class="text-sm text-gray-500 hover:text-gray-800">목록</a>
            <div class="flex gap-2">
                @if(auth()->id() === $this->post->user_id || session('bbs_admin_authenticated'))
                    <a href="{{ route('bbs.posts.edit', [$this->board->slug, $this->post->id]) }}"
                       class="rounded border border-gray-300 px-3 py-1 text-sm text-gray-700 hover:bg-white">
                        수정
                    </a>
                    <button wire:click="deletePost"
                            wire:confirm="정말 삭제하시겠습니까?"
                            class="rounded border border-red-200 px-3 py-1 text-sm text-red-500 hover:bg-red-50">
                        삭제
                    </button>
                @endif
            </div>
        </footer>
    </article>

    {{-- 댓글 섹션 --}}
    @if($this->board->use_comment)
        <section class="mt-8">
            <h3 class="mb-3 text-base font-semibold text-gray-800">
                댓글 <span class="text-blue-600">{{ $this->post->allComments()->count() }}</span>
            </h3>

            <div class="space-y-2">
                @foreach($comments as $comment)
                    @include('korean-bbs::components.comment-item', ['comment' => $comment, 'depth' => 0])
                    @foreach($comment->replies as $reply)
                        @include('korean-bbs::components.comment-item', ['comment' => $reply, 'depth' => 1])
                    @endforeach
                @endforeach
            </div>

            @if($this->canComment())
            <div class="mt-4 rounded-lg border border-gray-200 bg-white p-4">
                @if($replyToId)
                    <div class="mb-3 flex items-center gap-2 rounded-lg bg-blue-50 px-3 py-2 text-sm text-blue-700">
                        <span>답글 작성 중</span>
                        <button wire:click="setReplyTo(null)" class="ml-auto text-gray-400 hover:text-gray-600">취소</button>
                    </div>
                @endif

                @if(!auth()->check())
                    <div class="mb-3 grid grid-cols-1 gap-2 sm:grid-cols-2">
                        <input type="text" wire:model="commentAuthorName"
                               placeholder="이름 (필수)"
                               class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400">
                        <input type="password" wire:model="commentAuthorPassword"
                               placeholder="비밀번호 (필수)"
                               class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400">
                    </div>
                @endif

                <textarea wire:model="commentContent"
                          rows="3"
                          placeholder="댓글을 작성해주세요..."
                          class="w-full resize-none rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400"></textarea>

                @error('commentContent') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                @error('commentAuthorName') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                @error('commentAuthorPassword') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror

                <div class="mt-2 flex justify-end">
                    <button wire:click="submitComment"
                            class="rounded-lg bg-gray-900 px-5 py-2 text-sm text-white transition hover:bg-gray-700">
                        댓글 등록
                    </button>
                </div>
            </div>
            @endif
        </section>
    @endif

    @endif {{-- secretUnlocked --}}
</div>
