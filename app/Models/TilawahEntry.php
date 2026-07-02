<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TilawahEntry extends Model
{
    protected $fillable = ['user_id', 'date', 'pages', 'surah', 'note'];

    protected function casts(): array
    {
        return ['date' => 'date', 'pages' => 'integer'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
