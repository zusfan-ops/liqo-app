<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meeting extends Model
{
    protected $fillable = ['group_id', 'title', 'date', 'time', 'location', 'host', 'topic', 'note'];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}
