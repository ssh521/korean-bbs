<?php

namespace Ssh521\KoreanBbs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Like extends Model
{
    public $timestamps = false;

    protected $table = 'bbs_likes';

    protected $fillable = ['likeable_type', 'likeable_id', 'user_id', 'ip_address', 'type'];

    public function likeable(): MorphTo
    {
        return $this->morphTo();
    }
}
