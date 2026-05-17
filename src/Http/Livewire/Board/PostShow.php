<?php

namespace Ssh521\KoreanBbs\Http\Livewire\Board;

use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Ssh521\KoreanBbs\Models\Board;
use Ssh521\KoreanBbs\Models\Comment;
use Ssh521\KoreanBbs\Models\Like;
use Ssh521\KoreanBbs\Models\Post;

class PostShow extends Component
{
    public Board $board;
    public Post $post;

    // 댓글 작성 폼
    public string $commentContent = '';
    public string $commentAuthorName = '';
    public string $commentAuthorPassword = '';
    public ?int $replyToId = null;

    // 비밀글 잠금
    public bool $secretUnlocked = false;
    public string $secretPassword = '';

    public function mount(string $boardSlug, Post $post): void
    {
        $this->board = Board::where('slug', $boardSlug)->where('is_active', true)->firstOrFail();
        abort_if($post->board_id !== $this->board->id, 404);
        $this->post = $post;

        if (!$post->is_secret) {
            $this->secretUnlocked = true;
            $post->incrementViewCount();
        }
    }

    public function unlockSecret(): void
    {
        if (Hash::check($this->secretPassword, $this->post->getRawOriginal('author_password'))) {
            $this->secretUnlocked = true;
            $this->post->incrementViewCount();
        } else {
            $this->addError('secretPassword', '비밀번호가 올바르지 않습니다.');
        }
    }

    public function submitComment(): void
    {
        $rules = ['commentContent' => 'required|string|max:2000'];

        if (!auth()->check()) {
            $rules['commentAuthorName']     = 'required|string|max:20';
            $rules['commentAuthorPassword'] = 'required|string|min:4|max:20';
        }

        $this->validate($rules);

        $data = [
            'post_id'   => $this->post->id,
            'parent_id' => $this->replyToId,
            'content'   => $this->commentContent,
        ];

        if (auth()->check()) {
            $data['user_id'] = auth()->id();
        } else {
            $data['author_name']     = $this->commentAuthorName;
            $data['author_password'] = Hash::make($this->commentAuthorPassword);
        }

        Comment::create($data);

        $this->reset(['commentContent', 'commentAuthorName', 'commentAuthorPassword', 'replyToId']);
        $this->dispatch('comment-added');
    }

    public function setReplyTo(?int $commentId): void
    {
        $this->replyToId = $commentId;
    }

    public function toggleLike(string $type): void
    {
        $userId = auth()->id();
        $ip     = request()->ip();

        $existing = Like::where('likeable_type', Post::class)
            ->where('likeable_id', $this->post->id)
            ->when($userId, fn ($q) => $q->where('user_id', $userId))
            ->when(!$userId, fn ($q) => $q->where('ip_address', $ip))
            ->first();

        if ($existing) {
            if ($existing->type === $type) {
                return; // 이미 동일한 타입으로 반응
            }
            $existing->delete();
            $this->post->decrement($existing->type . '_count');
        }

        Like::create([
            'likeable_type' => Post::class,
            'likeable_id'   => $this->post->id,
            'user_id'       => $userId,
            'ip_address'    => !$userId ? $ip : null,
            'type'          => $type,
        ]);

        $this->post->increment($type . '_count');
        $this->post->refresh();
    }

    public function deletePost(): void
    {
        abort_unless(auth()->id() === $this->post->user_id || session('bbs_admin_authenticated'), 403);
        $this->post->delete();
        $this->redirect(route('bbs.posts.index', $this->board->slug));
    }

    public function render()
    {
        $comments = Comment::where('post_id', $this->post->id)
            ->whereNull('parent_id')
            ->with('replies.user', 'user')
            ->orderBy('created_at')
            ->get();

        return view('korean-bbs::board.show', compact('comments'))
            ->layout('korean-bbs::layouts.bbs');
    }
}
