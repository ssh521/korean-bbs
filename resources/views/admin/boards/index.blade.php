<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">게시판 관리</h2>
        <a href="{{ route('bbs.admin.boards.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            + 게시판 추가
        </a>
    </div>

    {{-- 검색 --}}
    <div class="mb-4">
        <input type="text" wire:model.live.debounce.300ms="search"
               placeholder="게시판 이름 검색..."
               class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-64 focus:outline-none focus:ring-1 focus:ring-blue-400">
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">게시판명</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">슬러그</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">타입</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">그룹</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">글수</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">상태</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">관리</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($boards as $board)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $board->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500 font-mono">{{ $board->slug }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-xs px-2 py-0.5 rounded-full font-mono
                                {{ $board->skin === 'gallery' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $board->skin }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center text-xs text-gray-500">{{ $board->group?->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600">{{ number_format($board->posts_count) }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-xs px-2 py-0.5 rounded-full
                                {{ $board->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                                {{ $board->is_active ? '활성' : '비활성' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('bbs.posts.index', $board->slug) }}" target="_blank"
                                    class="text-xs text-gray-400 hover:text-gray-600 px-2 py-1 border border-gray-200 rounded hover:bg-gray-50">
                                     보기
                                 </a>
                                 <a href="{{ route('bbs.admin.boards.edit', $board->id) }}"
                                   class="text-xs text-blue-600 hover:text-blue-800 px-2 py-1 border border-blue-300 rounded hover:bg-blue-50">
                                    수정
                                </a>
                                <button wire:click="delete({{ $board->id }})"
                                        wire:confirm="'{{ $board->name }}' 게시판을 정말 삭제하시겠습니까? 관련 게시글 {{ number_format($board->posts_count) }}개가 함께 삭제되며 복구할 수 없습니다."
                                        class="text-xs text-red-500 hover:text-red-700 px-2 py-1 border border-red-300 rounded hover:bg-red-50">
                                    삭제
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-sm text-gray-400">
                            게시판이 없습니다. 게시판을 추가해보세요.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $boards->links() }}
        </div>
    </div>
</div>
