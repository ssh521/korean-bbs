<?php

namespace Ssh521\KoreanBbs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Board extends Model
{
    protected $table = 'bbs_boards';

    protected $fillable = [
        'group_id', 'name', 'slug', 'description', 'skin', 'width',
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

    public function widthClass(): string
    {
        $width = trim((string) $this->width);

        if ($width === '') {
            return 'max-w-6xl mx-auto';
        }

        if ($this->isCssWidthValue($width)) {
            return 'mx-auto';
        }

        return 'mx-auto ' . $width;
    }

    public function widthStyle(): ?string
    {
        $width = trim((string) $this->width);

        if ($width === '' || !$this->isCssWidthValue($width)) {
            return null;
        }

        return "width: {$width}; max-width: 100%;";
    }

    private function isCssWidthValue(string $width): bool
    {
        return preg_match('/^(auto|[0-9]+(\.[0-9]+)?(px|%|rem|em|vw|vh|ch))$/', $width) === 1;
    }
}
