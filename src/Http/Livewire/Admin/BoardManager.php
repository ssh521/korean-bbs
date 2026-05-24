<?php

namespace Ssh521\KoreanBbs\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Ssh521\KoreanBbs\Models\Board;

class BoardManager extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        Board::findOrFail($id)->delete();
        session()->flash('success', '게시판이 삭제되었습니다.');
    }

    public function render()
    {
        $boards = Board::with('group')
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->withCount('posts')
            ->orderBy('order')
            ->paginate(20);

        return view('korean-bbs::admin.boards.index', compact('boards'))
            ->layout('korean-bbs::layouts.admin');
    }
}
