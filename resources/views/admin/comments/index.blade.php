<div>
    <h2 class="text-2xl font-bold text-gray-800 mb-6">댓글 관리</h2>

    <div class="mb-4">
        <input type="text" wire:model.live.debounce.300ms="search"
               placeholder="댓글 내용 검색..."
               class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-64 focus:outline-none focus:ring-1 focus:ring-blue-400">
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">내용</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">게시판/게시글</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">작성자</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">날짜</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">관리</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($comments as $comment)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3">
                            <div class="text-sm text-gray-700 max-w-sm truncate">
                                @if($comment->parent_id)
                                    <span class="text-blue-400 text-xs">↩ 답글: </span>
                                @endif
                                {{ $comment->content }}
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('bbs.posts.show', [$comment->post->board->slug ?? '#', $comment->post_id]) }}"
                               target="_blank"
                               class="text-xs text-blue-600 hover:underline">
                                {{ $comment->post->board?->name ?? '-' }} > {{ Str::limit($comment->post?->title ?? '-', 20) }}
                            </a>
                        </td>
                        <td class="px-4 py-3 text-center text-xs text-gray-600">{{ $comment->author_name }}</td>
                        <td class="px-4 py-3 text-center text-xs text-gray-400">{{ $comment->created_at->format('Y.m.d H:i') }}</td>
                        <td class="px-4 py-3 text-center">
                            <button wire:click="delete({{ $comment->id }})"
                                    wire:confirm="'{{ Str::limit($comment->content, 30) }}' 댓글을 정말 삭제하시겠습니까? 복구할 수 없습니다."
                                    class="text-xs text-red-500 hover:text-red-700 px-2 py-1 border border-red-300 rounded hover:bg-red-50">
                                삭제
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center text-sm text-gray-400">댓글이 없습니다.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-100">
            <x-korean-bbs::pagination :paginator="$comments" variant="admin" />
        </div>
    </div>
</div>
