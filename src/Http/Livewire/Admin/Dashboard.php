<?php

namespace Ssh521\KoreanBbs\Http\Livewire\Admin;

use Livewire\Component;
use Ssh521\KoreanBbs\Models\Board;
use Ssh521\KoreanBbs\Models\Comment;
use Ssh521\KoreanBbs\Models\Post;

class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'boards'        => Board::count(),
            'posts'         => Post::count(),
            'comments'      => Comment::count(),
            'recent_posts'  => Post::with('board')->latest()->limit(10)->get(),
        ];

        return view('korean-bbs::admin.dashboard', compact('stats'))
            ->layout('korean-bbs::layouts.admin');
    }
}
