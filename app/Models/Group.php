<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Group extends Model
{
    protected $fillable = ['name', 'code', 'city', 'country', 'tilawah_target'];

    protected function casts(): array
    {
        return ['tilawah_target' => 'integer'];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function meetings(): HasMany
    {
        return $this->hasMany(Meeting::class);
    }

    public static function generateCode(): string
    {
        do {
            // Tanpa karakter ambigu (0/O, 1/I/L) agar mudah dibagikan lisan
            $code = strtoupper(Str::password(6, letters: true, numbers: true, symbols: false));
            $code = strtr($code, ['0' => '2', 'O' => 'A', '1' => '3', 'I' => 'B', 'L' => 'C']);
        } while (static::where('code', $code)->exists());

        return $code;
    }
}
