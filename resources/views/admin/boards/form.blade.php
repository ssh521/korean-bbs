<div>
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('bbs.admin.boards.index') }}" class="text-gray-400 hover:text-gray-600">
            ←
        </a>
        <h2 class="text-2xl font-bold text-gray-800">
            {{ $this->board ? '게시판 수정' : '게시판 추가' }}
        </h2>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6 max-w-2xl">
        <div class="space-y-4">

            {{-- 이름 / 슬러그 --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">게시판 이름 <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="name"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-400 @error('name') border-red-400 @enderror">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">슬러그 (URL) <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="slug"
                           placeholder="영문/숫자/하이픈"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-1 focus:ring-blue-400 @error('slug') border-red-400 @enderror">
                    @error('slug') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- 설명 --}}
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">설명</label>
                <input type="text" wire:model="description"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-400">
            </div>

            {{-- 게시판 너비 --}}
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">게시판 width</label>
                <input type="text" wire:model="width"
                       placeholder="예: max-w-4xl, w-full, 100%, 600px"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-1 focus:ring-blue-400 @error('width') border-red-400 @enderror">
                <p class="text-xs text-gray-500 mt-1">
                    TailwindCSS 클래스 또는 CSS width 값을 입력할 수 있습니다. 예전 방식처럼 100%, 600px도 사용할 수 있습니다.
                </p>
                <p class="text-xs text-gray-400 mt-1">
                    예: max-w-6xl, max-w-screen-lg, w-full, w-[720px], 100%, 600px, 48rem
                </p>
                @error('width') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- 타입 / 그룹 --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">스킨</label>
                    <select wire:model="skin"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-1 focus:ring-blue-400">
                        @foreach($skins as $skinKey)
                            <option value="{{ $skinKey }}">{{ $skinKey }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">그룹</label>
                    <select wire:model="groupId"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-400">
                        <option value="0">그룹 없음</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- 권한 설정 --}}
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-xs font-medium text-gray-600 mb-3">권한 설정</div>
                <div class="grid grid-cols-3 gap-3">
                    @foreach([['label' => '글쓰기', 'model' => 'writeLevel'], ['label' => '댓글', 'model' => 'commentLevel'], ['label' => '파일', 'model' => 'fileLevel']] as $perm)
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">{{ $perm['label'] }} 권한</label>
                            <select wire:model="{{ $perm['model'] }}"
                                    class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs focus:outline-none">
                                <option value="0">비회원 가능</option>
                                <option value="1">회원 필요</option>
                            </select>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 기타 설정 --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">페이지당 글 수</label>
                    <input type="number" wire:model="postsPerPage" min="5" max="100"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">정렬 순서</label>
                    <input type="number" wire:model="order" min="0"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-400">
                </div>
            </div>

            {{-- 토글 옵션 --}}
            <div class="flex flex-wrap gap-6">
                @foreach([
                    ['label' => '비밀글 허용', 'model' => 'allowSecret'],
                    ['label' => '댓글 사용', 'model' => 'useComment'],
                    ['label' => '추천/비추천', 'model' => 'useLike'],
                    ['label' => '파일첨부', 'model' => 'useFile'],
                    ['label' => '게시판 활성화', 'model' => 'isActive'],
                ] as $toggle)
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" wire:model="{{ $toggle['model'] }}" class="rounded text-blue-600">
                        {{ $toggle['label'] }}
                    </label>
                @endforeach
            </div>

        </div>

        {{-- 버튼 --}}
        <div class="mt-6 flex justify-between">
            <a href="{{ route('bbs.admin.boards.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                취소
            </a>
            <button wire:click="save"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-6 py-2 rounded-lg transition">
                {{ $this->board ? '수정 완료' : '게시판 생성' }}
            </button>
        </div>
    </div>
</div>
