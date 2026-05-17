<?php

namespace Ssh521\KoreanBbs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $table = 'bbs_posts';

    protected $fillable = [
        'board_id', 'user_id', 'author_name', 'author_password',
        'title', 'content', 'is_notice', 'is_secret',
        'view_count', 'like_count', 'dislike_count', 'thumbnail_path',
    ];

    protected $casts = [
        'is_notice' => 'boolean',
        'is_secret' => 'boolean',
    ];

    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class, 'board_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model', \App\Models\User::class));
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id')->whereNull('parent_id')->orderBy('created_at');
    }

    public function allComments(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(BbsFile::class, 'post_id');
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function getAuthorNameAttribute($value): string
    {
        return $this->user?->name ?? $value ?? '익명';
    }

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }
}
