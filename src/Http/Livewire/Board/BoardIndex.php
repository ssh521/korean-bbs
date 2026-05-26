<?php

namespace Ssh521\KoreanBbs\Http\Livewire\Board;

use Illuminate\Support\Facades\Gate;
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

        $groups->each(function (BoardGroup $group): void {
            $group->setRelation(
                'boards',
                $group->boards->filter(fn (Board $board) => Gate::allows('korean-bbs.list', $board))->values()
            );
        });

        $groups = $groups->filter(fn (BoardGroup $group) => $group->boards->isNotEmpty())->values();

        $noGroupBoards = Board::whereNull('group_id')
            ->where('is_active', true)
            ->orderBy('order')
            ->get()
            ->filter(fn (Board $board) => Gate::allows('korean-bbs.list', $board))
            ->values();

        return view('korean-bbs::board.index', compact('groups', 'noGroupBoards'))
            ->layout(config('korean-bbs.layout'), [
                'title' => '게시판',
                'breadcrumbs' => [
                    ['label' => '게시판'],
                ],
            ]);
    }
}
