<?php

namespace Ssh521\KoreanBbs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class BbsFile extends Model
{
    protected $table = 'bbs_files';

    protected $fillable = [
        'post_id', 'original_name', 'stored_name', 'path',
        'mime_type', 'size', 'is_image', 'download_count',
    ];

    protected $casts = [
        'is_image' => 'boolean',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function url(): string
    {
        return Storage::disk(config('korean-bbs.upload.disk'))->url($this->path);
    }

    public function humanSize(): string
    {
        $bytes = $this->size;
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 1) . ' MB';
        }
        if ($bytes >= 1024) {
            return round($bytes / 1024, 1) . ' KB';
        }
        return $bytes . ' B';
    }
}
