<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = ['group_id', 'date', 'title', 'speaker', 'content'];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }
}
