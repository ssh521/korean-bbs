<div class="space-y-6">
    <x-korean-bbs::admin.page-header
        title="게시판 관리"
        description="게시판 생성, 활성 상태, 스킨 정보를 통합 관리합니다.">
        <x-slot name="actions">
            <x-korean-bbs::admin.button-link
                :href="route('bbs.admin.boards.create')"
                variant="primary">
                + 게시판 추가
            </x-korean-bbs::admin.button-link>
        </x-slot>
    </x-korean-bbs::admin.page-header>

    <x-korean-bbs::admin.filter-panel>
        <div class="max-w-md">
            <x-korean-bbs::admin.input
                id="board-search"
                label="게시판 검색"
                sr-only
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="게시판 이름 검색..." />
        </div>
    </x-korean-bbs::admin.filter-panel>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="hidden overflow-x-auto md:block">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <x-korean-bbs::admin.table-th>게시판명</x-korean-bbs::admin.table-th>
                        <x-korean-bbs::admin.table-th>슬러그</x-korean-bbs::admin.table-th>
                        <x-korean-bbs::admin.table-th class="text-center">스킨</x-korean-bbs::admin.table-th>
                        <x-korean-bbs::admin.table-th class="text-center">그룹</x-korean-bbs::admin.table-th>
                        <x-korean-bbs::admin.table-th class="text-center">글수</x-korean-bbs::admin.table-th>
                        <x-korean-bbs::admin.table-th class="text-center">상태</x-korean-bbs::admin.table-th>
                        <x-korean-bbs::admin.table-th class="text-center">관리</x-korean-bbs::admin.table-th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($boards as $board)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $board->name }}</td>
                            <td class="px-4 py-3 text-sm font-mono text-gray-500">{{ $board->slug }}</td>
                            <td class="px-4 py-3 text-center">
                                <x-korean-bbs::admin.badge variant="info">{{ $board->skin }}</x-korean-bbs::admin.badge>
                            </td>
                            <td class="px-4 py-3 text-center text-sm text-gray-600">{{ $board->group?->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-center text-sm text-gray-600">{{ number_format($board->posts_count) }}</td>
                            <td class="px-4 py-3 text-center">
                                <x-korean-bbs::admin.badge :variant="$board->is_active ? 'success' : 'danger'">
                                    {{ $board->is_active ? '활성' : '비활성' }}
                                </x-korean-bbs::admin.badge>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    <x-korean-bbs::admin.button-link
                                        :href="route('bbs.posts.index', $board->slug)"
                                        target="_blank"
                                        size="sm">
                                        보기
                                    </x-korean-bbs::admin.button-link>
                                    <x-korean-bbs::admin.button-link
                                        :href="route('bbs.admin.boards.edit', $board->id)"
                                        variant="primary"
                                        size="sm">
                                        수정
                                    </x-korean-bbs::admin.button-link>
                                    <x-korean-bbs::admin.button
                                        wire:click="delete({{ $board->id }})"
                                        wire:confirm="'{{ $board->name }}' 게시판을 정말 삭제하시겠습니까? 관련 게시글 {{ number_format($board->posts_count) }}개가 함께 삭제되며 복구할 수 없습니다."
                                        variant="danger-soft"
                                        size="sm">
                                        삭제
                                    </x-korean-bbs::admin.button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8">
                                <x-korean-bbs::admin.empty-state
                                    title="등록된 게시판이 없습니다."
                                    description="새 게시판을 추가하면 목록이 표시됩니다." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="space-y-3 p-4 md:hidden">
            @forelse($boards as $board)
                <article class="rounded-xl border border-gray-200 p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900">{{ $board->name }}</h3>
                            <p class="mt-1 text-xs font-mono text-gray-500">{{ $board->slug }}</p>
                        </div>
                        <x-korean-bbs::admin.badge :variant="$board->is_active ? 'success' : 'danger'">
                            {{ $board->is_active ? '활성' : '비활성' }}
                        </x-korean-bbs::admin.badge>
                    </div>
                    <dl class="mt-4 grid grid-cols-2 gap-3 text-xs">
                        <div>
                            <dt class="text-gray-500">스킨</dt>
                            <dd class="mt-1 font-medium text-gray-800">{{ $board->skin }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">그룹</dt>
                            <dd class="mt-1 font-medium text-gray-800">{{ $board->group?->name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">글수</dt>
                            <dd class="mt-1 font-medium text-gray-800">{{ number_format($board->posts_count) }}</dd>
                        </div>
                    </dl>
                    <div class="mt-4 flex flex-wrap justify-end gap-2">
                        <x-korean-bbs::admin.button-link
                            :href="route('bbs.posts.index', $board->slug)"
                            target="_blank"
                            size="sm">
                            보기
                        </x-korean-bbs::admin.button-link>
                        <x-korean-bbs::admin.button-link
                            :href="route('bbs.admin.boards.edit', $board->id)"
                            variant="primary"
                            size="sm">
                            수정
                        </x-korean-bbs::admin.button-link>
                        <x-korean-bbs::admin.button
                            wire:click="delete({{ $board->id }})"
                            wire:confirm="'{{ $board->name }}' 게시판을 정말 삭제하시겠습니까? 관련 게시글 {{ number_format($board->posts_count) }}개가 함께 삭제되며 복구할 수 없습니다."
                            variant="danger-soft"
                            size="sm">
                            삭제
                        </x-korean-bbs::admin.button>
                    </div>
                </article>
            @empty
                <x-korean-bbs::admin.empty-state
                    title="등록된 게시판이 없습니다."
                    description="새 게시판을 추가하면 목록이 표시됩니다." />
            @endforelse
        </div>

        <div class="border-t border-gray-100 px-4 py-3">
            <x-korean-bbs::pagination :paginator="$boards" variant="admin" />
        </div>
    </div>
</div>
