<div>
    {{-- 상단 네비게이션 --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('bbs.index') }}" class="hover:text-blue-600">게시판</a>
        <span>/</span>
        <a href="{{ route('bbs.posts.index', $this->board->slug) }}" class="hover:text-blue-600">{{ $this->board->name }}</a>
        <span>/</span>
        <span>{{ $this->post ? '수정' : '글쓰기' }}</span>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-6">
            {{ $this->post ? '게시글 수정' : '게시글 작성' }}
        </h2>

        {{-- 비회원 정보 --}}
        @if(!auth()->check())
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">이름 <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="authorName"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-400 @error('authorName') border-red-400 @enderror">
                    @error('authorName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">비밀번호 <span class="text-red-500">*</span></label>
                    <input type="password" wire:model="authorPassword"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-400 @error('authorPassword') border-red-400 @enderror">
                    @error('authorPassword') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        @endif

        {{-- 제목 --}}
        <div class="mb-4">
            <label class="block text-xs font-medium text-gray-600 mb-1">제목 <span class="text-red-500">*</span></label>
            <input type="text" wire:model="title"
                   placeholder="제목을 입력하세요"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-blue-400 @error('title') border-red-400 @enderror">
            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- 옵션 (관리자/설정에 따라) --}}
        <div class="flex gap-4 mb-4">
            @if(session('bbs_admin_authenticated'))
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                    <input type="checkbox" wire:model="isNotice" class="rounded">
                    공지글로 등록
                </label>
            @endif
            @if($this->board->allow_secret)
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                    <input type="checkbox" wire:model="isSecret" class="rounded">
                    비밀글
                </label>
            @endif
        </div>

        {{-- 내용 --}}
        <div class="mb-4">
            <label class="block text-xs font-medium text-gray-600 mb-1">내용 <span class="text-red-500">*</span></label>
            <textarea wire:model="content"
                      rows="12"
                      placeholder="내용을 입력하세요"
                      class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm resize-y focus:outline-none focus:ring-1 focus:ring-blue-400 @error('content') border-red-400 @enderror"></textarea>
            @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- 파일 첨부 --}}
        @if($this->board->use_file)
            <div class="mb-6">
                <label class="block text-xs font-medium text-gray-600 mb-1">파일 첨부</label>
                <input type="file" wire:model="uploadedFiles"
                       multiple
                       accept="{{ implode(',', array_map(fn($e) => '.' . $e, config('korean-bbs.upload.allowed_types', []))) }}"
                       class="text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="text-xs text-gray-400 mt-1">
                    최대 {{ config('korean-bbs.upload.max_size', 10240) / 1024 }}MB,
                    허용 형식: {{ implode(', ', config('korean-bbs.upload.allowed_types', [])) }}
                </p>
                @error('uploadedFiles.*') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        @endif

        {{-- 버튼 --}}
        <div class="flex justify-between">
            <a href="{{ route('bbs.posts.index', $this->board->slug) }}"
               class="text-sm text-gray-500 hover:text-gray-700 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                취소
            </a>
            <button wire:click="save"
                    wire:loading.attr="disabled"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-6 py-2 rounded-lg transition disabled:opacity-50">
                <span wire:loading.remove>{{ $this->post ? '수정 완료' : '등록' }}</span>
                <span wire:loading>처리중...</span>
            </button>
        </div>
    </div>
</div>
