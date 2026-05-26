<div class="space-y-6">
    <x-korean-bbs::admin.page-header
        title="댓글 관리"
        description="댓글을 검색하고 게시글 맥락을 확인한 뒤 삭제할 수 있습니다." />

    <x-korean-bbs::admin.filter-panel>
        <div class="max-w-md">
            <x-korean-bbs::admin.input
                id="comment-search"
                label="댓글 검색"
                sr-only
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="댓글 내용 검색..." />
        </div>
    </x-korean-bbs::admin.filter-panel>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="hidden overflow-x-auto md:block">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <x-korean-bbs::admin.table-th>내용</x-korean-bbs::admin.table-th>
                        <x-korean-bbs::admin.table-th class="text-center">게시판/게시글</x-korean-bbs::admin.table-th>
                        <x-korean-bbs::admin.table-th class="text-center">작성자</x-korean-bbs::admin.table-th>
                        <x-korean-bbs::admin.table-th class="text-center">날짜</x-korean-bbs::admin.table-th>
                        <x-korean-bbs::admin.table-th class="text-center">관리</x-korean-bbs::admin.table-th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($comments as $comment)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">
                                <div class="max-w-md truncate text-sm text-gray-800">
                                    @if($comment->parent_id)
                                        <span class="mr-1 text-xs text-blue-500">↩ 답글</span>
                                    @endif
                                    {{ $comment->content }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('bbs.posts.show', [$comment->post->board->slug ?? '#', $comment->post_id]) }}"
                                   target="_blank"
                                   class="text-sm text-blue-600 hover:underline">
                                    {{ $comment->post->board?->name ?? '-' }} > {{ Str::limit($comment->post?->title ?? '-', 20) }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-center text-sm text-gray-600">{{ $comment->author_name }}</td>
                            <td class="px-4 py-3 text-center text-sm text-gray-500">{{ $comment->created_at->format('Y.m.d H:i') }}</td>
                            <td class="px-4 py-3 text-center">
                                <x-korean-bbs::admin.button
                                    wire:click="delete({{ $comment->id }})"
                                    wire:confirm="'{{ Str::limit($comment->content, 30) }}' 댓글을 정말 삭제하시겠습니까? 복구할 수 없습니다."
                                    variant="danger-soft"
                                    size="sm">
                                    삭제
                                </x-korean-bbs::admin.button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8">
                                <x-korean-bbs::admin.empty-state
                                    title="댓글이 없습니다."
                                    description="검색 조건을 변경하거나 새 댓글 등록을 기다려주세요." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="space-y-3 p-4 md:hidden">
            @forelse($comments as $comment)
                <article class="rounded-xl border border-gray-200 p-4">
                    <div class="flex items-start justify-between gap-3">
                        <p class="text-sm text-gray-800">
                            @if($comment->parent_id)
                                <span class="mr-1 text-xs text-blue-500">↩ 답글</span>
                            @endif
                            {{ $comment->content }}
                        </p>
                        <x-korean-bbs::admin.button
                            wire:click="delete({{ $comment->id }})"
                            wire:confirm="'{{ Str::limit($comment->content, 30) }}' 댓글을 정말 삭제하시겠습니까? 복구할 수 없습니다."
                            variant="danger-soft"
                            size="sm">
                            삭제
                        </x-korean-bbs::admin.button>
                    </div>

                    <dl class="mt-3 grid grid-cols-1 gap-2 text-xs">
                        <div>
                            <dt class="text-gray-500">게시글</dt>
                            <dd class="mt-1">
                                <a href="{{ route('bbs.posts.show', [$comment->post->board->slug ?? '#', $comment->post_id]) }}"
                                   target="_blank"
                                   class="font-medium text-blue-600 hover:underline">
                                    {{ $comment->post->board?->name ?? '-' }} > {{ Str::limit($comment->post?->title ?? '-', 30) }}
                                </a>
                            </dd>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <dt class="text-gray-500">작성자</dt>
                                <dd class="mt-1 font-medium text-gray-800">{{ $comment->author_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-500">작성일</dt>
                                <dd class="mt-1 font-medium text-gray-800">{{ $comment->created_at->format('Y.m.d H:i') }}</dd>
                            </div>
                        </div>
                    </dl>
                </article>
            @empty
                <x-korean-bbs::admin.empty-state
                    title="댓글이 없습니다."
                    description="검색 조건을 변경하거나 새 댓글 등록을 기다려주세요." />
            @endforelse
        </div>

        <div class="border-t border-gray-100 px-4 py-3">
            <x-korean-bbs::pagination :paginator="$comments" variant="admin" />
        </div>
    </div>
</div>
