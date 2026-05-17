<div>
    {{-- 상단 네비게이션 --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('bbs.index') }}" class="hover:text-blue-600">게시판</a>
        <span>/</span>
        <a href="{{ route('bbs.posts.index', $this->board->slug) }}" class="hover:text-blue-600">{{ $this->board->name }}</a>
    </div>

    {{-- 비밀글 잠금 화면 --}}
    @if(!$secretUnlocked)
        <div class="bg-white border border-gray-200 rounded-xl p-12 text-center">
            <div class="text-gray-400 text-4xl mb-4">🔒</div>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">비밀글입니다</h3>
            <p class="text-sm text-gray-500 mb-6">작성자만 볼 수 있는 비밀글입니다.</p>
            <div class="flex gap-2 justify-center">
                <input type="password" wire:model="secretPassword"
                       wire:keydown.enter="unlockSecret"
                       placeholder="비밀번호"
                       class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-400">
                <button wire:click="unlockSecret"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                    확인
                </button>
            </div>
            @error('secretPassword')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>
    @else

    {{-- 게시글 본문 --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        {{-- 제목 영역 --}}
        <div class="px-6 py-5 border-b border-gray-100">
            <div class="flex items-start gap-3">
                @if($this->post->is_notice)
                    <span class="mt-0.5 text-xs bg-red-500 text-white px-2 py-0.5 rounded flex-shrink-0">공지</span>
                @endif
                <h1 class="text-xl font-bold text-gray-900 flex-1">{{ $this->post->title }}</h1>
            </div>
            <div class="flex flex-wrap items-center gap-4 mt-3 text-xs text-gray-500">
                <span class="font-medium text-gray-700">{{ $this->post->author_name }}</span>
                <span>{{ $this->post->created_at->format('Y.m.d H:i') }}</span>
                <span>조회 {{ number_format($this->post->view_count) }}</span>
                @if($this->post->like_count > 0)
                    <span class="text-blue-500">추천 {{ $this->post->like_count }}</span>
                @endif
            </div>
        </div>

        {{-- 첨부파일 --}}
        @if($this->post->files->isNotEmpty())
            <div class="px-6 py-3 border-b border-gray-100 bg-gray-50">
                <div class="text-xs text-gray-500 font-medium mb-2">첨부파일</div>
                <div class="flex flex-wrap gap-2">
                    @foreach($this->post->files as $file)
                        <a href="{{ route('bbs.file.download', $file->id) }}"
                           class="flex items-center gap-1.5 text-xs text-blue-600 hover:text-blue-800 bg-white border border-blue-200 rounded px-3 py-1.5 hover:bg-blue-50 transition">
                            @if($file->is_image)
                                <span>🖼</span>
                            @else
                                <span>📎</span>
                            @endif
                            {{ $file->original_name }}
                            <span class="text-gray-400">({{ $file->humanSize() }})</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- 본문 내용 --}}
        <div class="px-6 py-6 prose max-w-none text-gray-800 leading-relaxed min-h-32">
            {!! nl2br(e($this->post->content)) !!}
        </div>

        {{-- 이미지 미리보기 --}}
        @php $images = $this->post->files->where('is_image', true); @endphp
        @if($images->isNotEmpty())
            <div class="px-6 pb-4 flex flex-wrap gap-3">
                @foreach($images as $img)
                    <img src="{{ $img->url() }}" alt="{{ $img->original_name }}"
                         class="max-h-64 rounded-lg border border-gray-200 cursor-pointer hover:opacity-90"
                         onclick="window.open(this.src)">
                @endforeach
            </div>
        @endif

        {{-- 추천/비추천 --}}
        @if($this->board->use_like)
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-center gap-4">
                <button wire:click="toggleLike('like')"
                        class="flex items-center gap-2 px-6 py-2 rounded-full border-2 border-blue-400 text-blue-600 hover:bg-blue-50 transition font-medium text-sm">
                    👍 추천 <span class="font-bold">{{ $this->post->like_count }}</span>
                </button>
                <button wire:click="toggleLike('dislike')"
                        class="flex items-center gap-2 px-6 py-2 rounded-full border-2 border-red-300 text-red-500 hover:bg-red-50 transition font-medium text-sm">
                    👎 비추천 <span class="font-bold">{{ $this->post->dislike_count }}</span>
                </button>
            </div>
        @endif

        {{-- 수정/삭제 버튼 --}}
        <div class="px-6 py-3 border-t border-gray-100 flex justify-between items-center bg-gray-50">
            <a href="{{ route('bbs.posts.index', $this->board->slug) }}"
               class="text-sm text-gray-500 hover:text-gray-700">← 목록</a>
            <div class="flex gap-2">
                @if(auth()->id() === $this->post->user_id || session('bbs_admin_authenticated'))
                    <a href="{{ route('bbs.posts.edit', [$this->board->slug, $this->post->id]) }}"
                       class="text-sm text-blue-600 hover:text-blue-800 px-3 py-1 border border-blue-300 rounded hover:bg-blue-50">
                        수정
                    </a>
                    <button wire:click="deletePost"
                            wire:confirm="정말 삭제하시겠습니까?"
                            class="text-sm text-red-500 hover:text-red-700 px-3 py-1 border border-red-300 rounded hover:bg-red-50">
                        삭제
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- 댓글 섹션 --}}
    @if($this->board->use_comment)
        <div class="mt-6">
            <h3 class="text-base font-semibold text-gray-700 mb-3">
                댓글 <span class="text-blue-500">{{ $this->post->allComments()->count() }}</span>
            </h3>

            {{-- 댓글 목록 --}}
            <div class="space-y-2">
                @foreach($comments as $comment)
                    @include('korean-bbs::components.comment-item', ['comment' => $comment, 'depth' => 0])
                    @foreach($comment->replies as $reply)
                        @include('korean-bbs::components.comment-item', ['comment' => $reply, 'depth' => 1])
                    @endforeach
                @endforeach
            </div>

            {{-- 댓글 작성 폼 --}}
            <div class="mt-4 bg-white border border-gray-200 rounded-xl p-4">
                @if($replyToId)
                    <div class="flex items-center gap-2 mb-3 text-sm text-blue-600 bg-blue-50 px-3 py-2 rounded-lg">
                        <span>↩ 답글 작성 중</span>
                        <button wire:click="setReplyTo(null)" class="text-gray-400 hover:text-gray-600 ml-auto">✕</button>
                    </div>
                @endif

                @if(!auth()->check())
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <input type="text" wire:model="commentAuthorName"
                               placeholder="이름 (필수)"
                               class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-400">
                        <input type="password" wire:model="commentAuthorPassword"
                               placeholder="비밀번호 (필수)"
                               class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-400">
                    </div>
                @endif

                <textarea wire:model="commentContent"
                          rows="3"
                          placeholder="댓글을 작성해주세요..."
                          class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 resize-none focus:outline-none focus:ring-1 focus:ring-blue-400"></textarea>

                @error('commentContent') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                @error('commentAuthorName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                @error('commentAuthorPassword') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                <div class="mt-2 flex justify-end">
                    <button wire:click="submitComment"
                            class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-5 py-2 rounded-lg transition">
                        댓글 등록
                    </button>
                </div>
            </div>
        </div>
    @endif

    @endif {{-- secretUnlocked --}}
</div>
