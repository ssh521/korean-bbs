<?php

namespace Ssh521\KoreanBbs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BoardGroup extends Model
{
    protected $table = 'bbs_groups';

    protected $fillable = ['name', 'slug', 'order'];

    public function boards(): HasMany
    {
        return $this->hasMany(Board::class, 'group_id')->orderBy('order');
    }
}
