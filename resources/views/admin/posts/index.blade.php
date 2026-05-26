<div class="space-y-6">
    <x-korean-bbs::admin.page-header
        title="게시글 관리"
        description="게시글을 검색하고 상태를 확인한 뒤 개별 또는 일괄 삭제할 수 있습니다.">
        <x-slot name="actions">
            @if(!empty($selected))
                <x-korean-bbs::admin.button
                    wire:click="deleteSelected"
                    wire:confirm="선택한 게시글 {{ count($selected) }}개를 정말 삭제하시겠습니까? 관련 댓글과 첨부 데이터가 함께 삭제될 수 있으며 복구할 수 없습니다."
                    variant="danger">
                    선택 삭제 ({{ count($selected) }})
                </x-korean-bbs::admin.button>
            @endif
        </x-slot>
    </x-korean-bbs::admin.page-header>

    <x-korean-bbs::admin.filter-panel>
        <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
            <x-korean-bbs::admin.input
                id="post-search"
                label="게시글 검색"
                sr-only
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="제목 검색..." />
        </div>
    </x-korean-bbs::admin.filter-panel>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="hidden overflow-x-auto md:block">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 w-10">
                            <input type="checkbox" wire:model.live="selectAll" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <x-korean-bbs::admin.table-th>제목</x-korean-bbs::admin.table-th>
                        <x-korean-bbs::admin.table-th class="text-center">게시판</x-korean-bbs::admin.table-th>
                        <x-korean-bbs::admin.table-th class="text-center">작성자</x-korean-bbs::admin.table-th>
                        <x-korean-bbs::admin.table-th class="text-center">조회</x-korean-bbs::admin.table-th>
                        <x-korean-bbs::admin.table-th class="text-center">날짜</x-korean-bbs::admin.table-th>
                        <x-korean-bbs::admin.table-th class="text-center">관리</x-korean-bbs::admin.table-th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($posts as $post)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">
                                <input type="checkbox" wire:model.live="selected" value="{{ $post->id }}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    @if($post->is_notice)
                                        <x-korean-bbs::admin.badge variant="warning">공지</x-korean-bbs::admin.badge>
                                    @endif
                                    <a href="{{ route('bbs.posts.show', [$post->board->slug ?? '#', $post->id]) }}"
                                        target="_blank"
                                        class="max-w-xs truncate text-sm font-medium text-gray-800 hover:text-blue-600">
                                        {{ $post->title }}
                                    </a>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center text-sm text-gray-600">{{ $post->board?->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-center text-sm text-gray-600">{{ $post->author_name }}</td>
                            <td class="px-4 py-3 text-center text-sm text-gray-500">{{ number_format($post->view_count) }}</td>
                            <td class="px-4 py-3 text-center text-sm text-gray-500">{{ $post->created_at->format('Y.m.d') }}</td>
                            <td class="px-4 py-3 text-center">
                                <x-korean-bbs::admin.button
                                    wire:click="delete({{ $post->id }})"
                                    wire:confirm="'{{ $post->title }}' 게시글을 정말 삭제하시겠습니까? 관련 댓글과 첨부 데이터가 함께 삭제될 수 있으며 복구할 수 없습니다."
                                    variant="danger-soft"
                                    size="sm">
                                    삭제
                                </x-korean-bbs::admin.button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8">
                                <x-korean-bbs::admin.empty-state
                                    title="게시글이 없습니다."
                                    description="검색어를 변경하거나 새 게시글을 작성해보세요." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="space-y-3 p-4 md:hidden">
            @forelse($posts as $post)
                <article class="rounded-xl border border-gray-200 p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-start gap-2">
                            <input type="checkbox" wire:model.live="selected" value="{{ $post->id }}" class="mt-0.5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <div>
                                <div class="flex items-center gap-2">
                                    @if($post->is_notice)
                                        <x-korean-bbs::admin.badge variant="warning">공지</x-korean-bbs::admin.badge>
                                    @endif
                                    <a href="{{ route('bbs.posts.show', [$post->board->slug ?? '#', $post->id]) }}"
                                        target="_blank"
                                        class="text-sm font-semibold text-gray-900 hover:text-blue-600">
                                        {{ $post->title }}
                                    </a>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">{{ $post->board?->name ?? '-' }} · {{ $post->author_name }}</p>
                            </div>
                        </div>
                        <x-korean-bbs::admin.button
                            wire:click="delete({{ $post->id }})"
                            wire:confirm="'{{ $post->title }}' 게시글을 정말 삭제하시겠습니까? 관련 댓글과 첨부 데이터가 함께 삭제될 수 있으며 복구할 수 없습니다."
                            variant="danger-soft"
                            size="sm">
                            삭제
                        </x-korean-bbs::admin.button>
                    </div>
                    <dl class="mt-3 grid grid-cols-2 gap-3 text-xs">
                        <div>
                            <dt class="text-gray-500">조회수</dt>
                            <dd class="mt-1 font-medium text-gray-800">{{ number_format($post->view_count) }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">작성일</dt>
                            <dd class="mt-1 font-medium text-gray-800">{{ $post->created_at->format('Y.m.d') }}</dd>
                        </div>
                    </dl>
                </article>
            @empty
                <x-korean-bbs::admin.empty-state
                    title="게시글이 없습니다."
                    description="검색어를 변경하거나 새 게시글을 작성해보세요." />
            @endforelse
        </div>

        <div class="border-t border-gray-100 px-4 py-3">
            <x-korean-bbs::pagination :paginator="$posts" variant="admin" />
        </div>
    </div>
</div>
