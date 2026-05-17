<div class="bg-white border border-gray-200 rounded-lg p-4 {{ $depth > 0 ? 'ml-8 border-l-4 border-l-blue-200 bg-blue-50/30' : '' }}">
    <div class="flex items-start justify-between gap-3">
        <div class="flex-1">
            <div class="flex items-center gap-2 mb-1.5">
                @if($depth > 0)
                    <span class="text-blue-400 text-sm">↩</span>
                @endif
                <span class="text-sm font-medium text-gray-700">{{ $comment->author_name }}</span>
                <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
            </div>
            <div class="text-sm text-gray-800 leading-relaxed">
                @if($comment->trashed())
                    <span class="text-gray-400 italic">삭제된 댓글입니다.</span>
                @else
                    {{ $comment->content }}
                @endif
            </div>
        </div>

        @if(!$comment->trashed())
            <div class="flex items-center gap-1 flex-shrink-0">
                @if($depth === 0)
                    <button wire:click="setReplyTo({{ $comment->id }})"
                            class="text-xs text-gray-400 hover:text-blue-500 px-2 py-1 rounded hover:bg-blue-50">
                        답글
                    </button>
                @endif
                @if(auth()->id() === $comment->user_id || session('bbs_admin_authenticated'))
                    <button
                        wire:click="$dispatch('delete-comment', { id: {{ $comment->id }} })"
                        class="text-xs text-gray-400 hover:text-red-500 px-2 py-1 rounded hover:bg-red-50">
                        삭제
                    </button>
                @endif
            </div>
        @endif
    </div>
</div>
