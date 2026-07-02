<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = ['group_id', 'title', 'body', 'pinned', 'user_id'];

    protected function casts(): array
    {
        return ['pinned' => 'boolean'];
    }
}
