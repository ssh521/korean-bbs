<?php

namespace Ssh521\KoreanBbs\Http\Livewire\Admin;

use Livewire\Attributes\Confirm;
use Livewire\Component;
use Livewire\WithPagination;
use Ssh521\KoreanBbs\Models\Post;

class PostManager extends Component
{
    use WithPagination;

    public string $search = '';
    public int $boardFilter = 0;

    public array $selected = [];
    public bool $selectAll = false;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSelectAll(bool $value): void
    {
        $this->selected = $value ? $this->getPagePostIds() : [];
    }

    private function getPagePostIds(): array
    {
        return Post::when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->boardFilter, fn ($q) => $q->where('board_id', $this->boardFilter))
            ->latest()
            ->paginate(20)
            ->pluck('id')
            ->toArray();
    }

    #[Confirm('선택한 게시글을 삭제하시겠습니까?')]
    public function deleteSelected(): void
    {
        Post::whereIn('id', $this->selected)->delete();
        $this->selected  = [];
        $this->selectAll = false;
        session()->flash('success', '선택한 게시글이 삭제되었습니다.');
    }

    #[Confirm('이 게시글을 삭제하시겠습니까?')]
    public function delete(int $id): void
    {
        Post::findOrFail($id)->delete();
        session()->flash('success', '게시글이 삭제되었습니다.');
    }

    public function render()
    {
        $posts = Post::with('board')
            ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->boardFilter, fn ($q) => $q->where('board_id', $this->boardFilter))
            ->latest()
            ->paginate(20);

        return view('korean-bbs::admin.posts.index', compact('posts'))
            ->layout('korean-bbs::layouts.admin');
    }
}
