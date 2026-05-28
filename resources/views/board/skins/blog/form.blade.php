<div class="{{ $this->board->widthClass() }}" style="{{ $this->board->widthStyle() }}">
    <form wire:submit.prevent="save" class="rounded-lg border border-gray-200 bg-white">
        <div class="border-b border-gray-100 px-5 py-5 md:px-8">
            <p class="text-xs font-semibold tracking-wide text-blue-600">블로그 에디터</p>
            <h2 class="mt-2 text-2xl font-bold text-gray-900">
                {{ $this->post ? '블로그 글 수정' : '블로그 글 작성' }}
            </h2>
        </div>

        <div class="space-y-5 px-5 py-6 md:px-8">
            {{-- 비회원 정보 --}}
            @if(!auth()->check())
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-600">이름 <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="authorName"
                               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400 @error('authorName') border-red-400 @enderror">
                        @error('authorName') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-600">비밀번호 <span class="text-red-500">*</span></label>
                        <input type="password" wire:model="authorPassword"
                               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400 @error('authorPassword') border-red-400 @enderror">
                        @error('authorPassword') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            @endif

            {{-- 제목 --}}
            <div>
                <label class="mb-1 block text-xs font-medium text-gray-600">제목 <span class="text-red-500">*</span></label>
                <input type="text" wire:model="title"
                       placeholder="글 제목을 입력하세요"
                       class="w-full rounded-lg border border-gray-300 px-3 py-3 text-base font-semibold focus:outline-none focus:ring-1 focus:ring-gray-400 @error('title') border-red-400 @enderror">
                @error('title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- 옵션 --}}
            <div class="flex flex-wrap gap-4 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3">
                @if(session('bbs_admin_authenticated'))
                    <label class="flex cursor-pointer items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" wire:model="isNotice" class="rounded">
                        공지글로 등록
                    </label>
                @endif
                @if($this->board->allow_secret)
                    <label class="flex cursor-pointer items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" wire:model="isSecret" class="rounded">
                        비밀글
                    </label>
                @endif
            </div>

            {{-- 내용 --}}
            <div>
                <label class="mb-1 block text-xs font-medium text-gray-600">내용 <span class="text-red-500">*</span></label>
                @include($editorView ?? 'korean-bbs::editors.textarea')
                @error('content') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- 파일 첨부 --}}
            @if($this->canUploadFile())
                <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 p-4">
                    <label class="mb-2 block text-xs font-medium text-gray-600">대표 이미지 및 파일 첨부</label>
                    <input type="file" wire:model="uploadedFiles"
                           multiple
                           accept="{{ implode(',', array_map(fn($e) => '.' . $e, config('korean-bbs.upload.allowed_types', []))) }}"
                           class="text-sm text-gray-600 file:mr-3 file:rounded file:border-0 file:bg-gray-900 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-white hover:file:bg-gray-700">
                    <p class="mt-2 text-xs text-gray-400">
                        최대 {{ config('korean-bbs.upload.max_size', 10240) / 1024 }}MB,
                        허용 형식: {{ implode(', ', config('korean-bbs.upload.allowed_types', [])) }}
                    </p>
                    @error('uploadedFiles.*') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            @endif

            @include('korean-bbs::components.captcha')
        </div>

        <div class="flex items-center justify-between border-t border-gray-100 bg-gray-50 px-5 py-4 md:px-8">
            <a href="{{ route('bbs.posts.index', $this->board->slug) }}"
               class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-white hover:text-gray-900">
                취소
            </a>
            <button type="submit"
                    wire:loading.attr="disabled"
                    wire:target="save"
                    class="rounded-lg bg-gray-900 px-6 py-2 text-sm font-medium text-white transition hover:bg-gray-700 disabled:opacity-50">
                <span wire:loading.remove wire:target="save">{{ $this->post ? '수정 완료' : '등록' }}</span>
                <span wire:loading wire:target="save">처리중...</span>
            </button>
        </div>
    </form>
</div>
