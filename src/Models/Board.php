<?php

namespace Ssh521\KoreanBbs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Board extends Model
{
    protected $table = 'bbs_boards';

    protected $fillable = [
        'group_id', 'name', 'slug', 'description', 'type',
        'write_level', 'comment_level', 'file_level',
        'posts_per_page', 'allow_secret', 'use_comment',
        'use_like', 'use_file', 'is_active', 'order',
    ];

    protected $casts = [
        'allow_secret' => 'boolean',
        'use_comment'  => 'boolean',
        'use_like'     => 'boolean',
        'use_file'     => 'boolean',
        'is_active'    => 'boolean',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(BoardGroup::class, 'group_id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'board_id');
    }

    public function isGallery(): bool
    {
        return $this->type === 'gallery';
    }
}
