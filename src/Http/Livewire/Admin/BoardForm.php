<?php

namespace Ssh521\KoreanBbs\Http\Livewire\Admin;

use Livewire\Component;
use Ssh521\KoreanBbs\Models\Board;
use Ssh521\KoreanBbs\Models\BoardGroup;

class BoardForm extends Component
{
    public ?Board $board = null;

    public string $name = '';
    public string $slug = '';
    public string $description = '';
    public string $skin = 'list';
    public string $width = '';
    public int $groupId = 0;
    public int $writeLevel = 0;
    public int $commentLevel = 0;
    public int $fileLevel = 0;
    public int $postsPerPage = 20;
    public bool $allowSecret = false;
    public bool $useComment = true;
    public bool $useLike = true;
    public bool $useFile = true;
    public bool $isActive = true;
    public int $order = 0;

    public function mount(?Board $board = null): void
    {
        if ($board && $board->exists) {
            $this->board        = $board;
            $this->name         = $board->name;
            $this->slug         = $board->slug;
            $this->description  = $board->description ?? '';
            $this->skin         = $board->skin;
            $this->width        = $board->width ?? '';
            $this->groupId      = $board->group_id ?? 0;
            $this->writeLevel   = $board->write_level;
            $this->commentLevel = $board->comment_level;
            $this->fileLevel    = $board->file_level;
            $this->postsPerPage = $board->posts_per_page;
            $this->allowSecret  = $board->allow_secret;
            $this->useComment   = $board->use_comment;
            $this->useLike      = $board->use_like;
            $this->useFile      = $board->use_file;
            $this->isActive     = $board->is_active;
            $this->order        = $board->order;
        }
    }

    public function save(): void
    {
        $this->validate([
            'name'         => 'required|string|max:50',
            'slug'         => 'required|string|max:50|alpha_dash|unique:bbs_boards,slug' . ($this->board ? ',' . $this->board->id : ''),
            'skin'         => 'required|in:' . implode(',', config('korean-bbs.skins.allowed', ['list', 'gallery'])),
            'width'        => 'nullable|string|max:100|regex:/^[A-Za-z0-9\\s_:\\.\\-\\[\\]\\/\\%]+$/',
            'postsPerPage' => 'required|integer|min:5|max:100',
        ], [
            'width.regex' => '게시판 width는 TailwindCSS 클래스 또는 100%, 600px 같은 CSS width 값으로 입력해주세요.',
        ]);

        $data = [
            'name'          => $this->name,
            'slug'          => $this->slug,
            'description'   => $this->description ?: null,
            'skin'          => $this->skin,
            'width'         => trim($this->width) ?: null,
            'group_id'      => $this->groupId ?: null,
            'write_level'   => $this->writeLevel,
            'comment_level' => $this->commentLevel,
            'file_level'    => $this->fileLevel,
            'posts_per_page' => $this->postsPerPage,
            'allow_secret'  => $this->allowSecret,
            'use_comment'   => $this->useComment,
            'use_like'      => $this->useLike,
            'use_file'      => $this->useFile,
            'is_active'     => $this->isActive,
            'order'         => $this->order,
        ];

        if ($this->board) {
            $this->board->update($data);
            session()->flash('success', '게시판이 수정되었습니다.');
        } else {
            Board::create($data);
            session()->flash('success', '게시판이 생성되었습니다.');
        }

        $this->redirect(route('bbs.admin.boards.index'));
    }

    public function render()
    {
        $groups = BoardGroup::orderBy('order')->get();
        $skins = config('korean-bbs.skins.allowed', ['list', 'gallery']);

        return view('korean-bbs::admin.boards.form', compact('groups', 'skins'))
            ->layout('korean-bbs::layouts.admin');
    }
}
