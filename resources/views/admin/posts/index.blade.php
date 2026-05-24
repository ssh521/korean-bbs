<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">게시글 관리</h2>
        @if(!empty($selected))
            <button wire:click="deleteSelected"
                    wire:confirm="선택한 게시글 {{ count($selected) }}개를 정말 삭제하시겠습니까? 관련 댓글과 첨부 데이터가 함께 삭제될 수 있으며 복구할 수 없습니다."
                    class="bg-red-500 hover:bg-red-600 text-white text-sm px-4 py-2 rounded-lg transition">
                선택 삭제 ({{ count($selected) }})
            </button>
        @endif
    </div>

    <div class="mb-4">
        <input type="text" wire:model.live.debounce.300ms="search"
               placeholder="제목 검색..."
               class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-64 focus:outline-none focus:ring-1 focus:ring-blue-400">
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 w-8">
                        <input type="checkbox" wire:model.live="selectAll" class="rounded">
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">제목</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">게시판</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">작성자</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">조회</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">날짜</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">관리</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($posts as $post)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3">
                            <input type="checkbox" wire:model.live="selected" value="{{ $post->id }}" class="rounded">
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                @if($post->is_notice)
                                    <span class="text-xs bg-red-500 text-white px-1.5 py-0.5 rounded">공지</span>
                                @endif
                                <a href="{{ route('bbs.posts.show', [$post->board->slug ?? '#', $post->id]) }}"
                                   target="_blank"
                                   class="text-sm text-gray-700 hover:text-blue-600 truncate max-w-xs">
                                    {{ $post->title }}
                                </a>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center text-xs text-gray-500">{{ $post->board?->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-center text-xs text-gray-600">{{ $post->author_name }}</td>
                        <td class="px-4 py-3 text-center text-xs text-gray-500">{{ number_format($post->view_count) }}</td>
                        <td class="px-4 py-3 text-center text-xs text-gray-400">{{ $post->created_at->format('Y.m.d') }}</td>
                        <td class="px-4 py-3 text-center">
                            <button wire:click="delete({{ $post->id }})"
                                    wire:confirm="'{{ $post->title }}' 게시글을 정말 삭제하시겠습니까? 관련 댓글과 첨부 데이터가 함께 삭제될 수 있으며 복구할 수 없습니다."
                                    class="text-xs text-red-500 hover:text-red-700 px-2 py-1 border border-red-300 rounded hover:bg-red-50">
                                삭제
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-sm text-gray-400">게시글이 없습니다.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $posts->links() }}
        </div>
    </div>
</div>
