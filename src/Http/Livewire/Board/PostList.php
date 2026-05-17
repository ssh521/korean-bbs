<?php

namespace Ssh521\KoreanBbs\Http\Livewire\Board;

use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Ssh521\KoreanBbs\Models\Board;
use Ssh521\KoreanBbs\Models\Post;
use Ssh521\KoreanBbs\SkinResolver;

class PostList extends Component
{
    use WithPagination;

    public Board $board;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $searchType = 'title';

    public function mount(string $boardSlug): void
    {
        $this->board = Board::where('slug', $boardSlug)->where('is_active', true)->firstOrFail();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $skin = $this->board->skin;
        $perPage = $skin === 'gallery'
            ? config('korean-bbs.defaults.gallery_per_page', 12)
            : $this->board->posts_per_page;

        $notices = Post::where('board_id', $this->board->id)
            ->where('is_notice', true)
            ->latest()
            ->get();

        $query = Post::where('board_id', $this->board->id)
            ->where('is_notice', false)
            ->withCount('allComments');

        if ($this->search !== '') {
            $query->when($this->searchType === 'title', fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
                  ->when($this->searchType === 'content', fn ($q) => $q->where('content', 'like', "%{$this->search}%"))
                  ->when($this->searchType === 'author', fn ($q) => $q->where('author_name', 'like', "%{$this->search}%"));
        }

        $posts = $query->latest()->paginate($perPage);

        $view = SkinResolver::resolve($skin, 'list');

        return view($view, compact('posts', 'notices'))
            ->layout(config('korean-bbs.layout'));
    }
}
