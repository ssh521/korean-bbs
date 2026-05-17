<?php

namespace Ssh521\KoreanBbs\Http\Livewire\Board;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Ssh521\KoreanBbs\Models\Board;
use Ssh521\KoreanBbs\Models\BbsFile;
use Ssh521\KoreanBbs\Models\Post;

class PostForm extends Component
{
    use WithFileUploads;

    public Board $board;
    public ?Post $post = null;

    public string $title = '';
    public string $content = '';
    public bool $isNotice = false;
    public bool $isSecret = false;
    public string $authorName = '';
    public string $authorPassword = '';

    public array $uploadedFiles = [];

    public function mount(string $boardSlug, ?Post $post = null): void
    {
        $this->board = Board::where('slug', $boardSlug)->where('is_active', true)->firstOrFail();

        if ($post && $post->exists) {
            $this->post    = $post;
            $this->title   = $post->title;
            $this->content = $post->content;
            $this->isNotice = $post->is_notice;
            $this->isSecret = $post->is_secret;
        }
    }

    public function save(): void
    {
        $rules = [
            'title'         => 'required|string|max:200',
            'content'       => 'required|string',
            'uploadedFiles' => 'nullable|array',
            'uploadedFiles.*' => 'file|max:' . config('korean-bbs.upload.max_size'),
        ];

        if (!auth()->check()) {
            $rules['authorName']     = 'required|string|max:20';
            $rules['authorPassword'] = 'required|string|min:4|max:20';
        }

        $this->validate($rules);

        $data = [
            'board_id'  => $this->board->id,
            'title'     => $this->title,
            'content'   => $this->content,
            'is_notice' => session('bbs_admin_authenticated') && $this->isNotice,
            'is_secret' => $this->board->allow_secret && $this->isSecret,
        ];

        if (auth()->check()) {
            $data['user_id'] = auth()->id();
        } else {
            $data['author_name']     = $this->authorName;
            $data['author_password'] = Hash::make($this->authorPassword);
        }

        if ($this->post) {
            $this->post->update($data);
            $savedPost = $this->post;
        } else {
            $savedPost = Post::create($data);
        }

        $this->handleFileUploads($savedPost);

        $this->redirect(route('bbs.posts.show', [$this->board->slug, $savedPost->id]));
    }

    private function handleFileUploads(Post $post): void
    {
        if (empty($this->uploadedFiles)) {
            return;
        }

        $disk        = config('korean-bbs.upload.disk');
        $basePath    = config('korean-bbs.upload.path');
        $imageTypes  = config('korean-bbs.upload.image_types');
        $allowedExts = config('korean-bbs.upload.allowed_types');

        foreach ($this->uploadedFiles as $file) {
            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, $allowedExts)) {
                continue;
            }

            $storedName = $file->store($basePath . '/' . $post->board_id, $disk);
            $isImage    = in_array($ext, $imageTypes);

            BbsFile::create([
                'post_id'       => $post->id,
                'original_name' => $file->getClientOriginalName(),
                'stored_name'   => basename($storedName),
                'path'          => $storedName,
                'mime_type'     => $file->getMimeType(),
                'size'          => $file->getSize(),
                'is_image'      => $isImage,
            ]);

            // 갤러리형 게시판 썸네일 설정
            if ($isImage && !$post->thumbnail_path) {
                $post->update(['thumbnail_path' => $storedName]);
            }
        }
    }

    public function render()
    {
        return view('korean-bbs::board.form')
            ->layout('korean-bbs::layouts.bbs');
    }
}
