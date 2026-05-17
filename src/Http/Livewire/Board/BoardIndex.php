<?php

namespace Ssh521\KoreanBbs\Http\Livewire\Board;

use Livewire\Component;
use Ssh521\KoreanBbs\Models\Board;
use Ssh521\KoreanBbs\Models\BoardGroup;

class BoardIndex extends Component
{
    public function render()
    {
        $groups = BoardGroup::with(['boards' => fn ($q) => $q->where('is_active', true)->orderBy('order')])
            ->orderBy('order')
            ->get();

        $noGroupBoards = Board::whereNull('group_id')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        return view('korean-bbs::board.index', compact('groups', 'noGroupBoards'))
            ->layout('korean-bbs::layouts.bbs');
    }
}
