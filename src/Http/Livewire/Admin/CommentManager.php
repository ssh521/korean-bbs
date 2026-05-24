<?php

namespace Ssh521\KoreanBbs\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Ssh521\KoreanBbs\Models\Comment;

class CommentManager extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        Comment::findOrFail($id)->delete();
        session()->flash('success', '댓글이 삭제되었습니다.');
    }

    public function render()
    {
        $comments = Comment::with(['post.board', 'user'])
            ->when($this->search, fn ($q) => $q->where('content', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(30);

        return view('korean-bbs::admin.comments.index', compact('comments'))
            ->layout('korean-bbs::layouts.admin');
    }
}
