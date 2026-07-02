<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['group_id', 'name', 'email', 'password', 'role', 'phone', 'address', 'join_date'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLES = ['Koordinator', 'Sekretaris', 'Bendahara', 'Anggota'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'join_date' => 'date',
        ];
    }

    public function group(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function tilawahEntries(): HasMany
    {
        return $this->hasMany(TilawahEntry::class);
    }

    public function isKoordinator(): bool
    {
        return $this->role === 'Koordinator';
    }

    public function initials(): string
    {
        return collect(preg_split('/\s+/', trim($this->name)))
            ->take(2)
            ->map(fn ($s) => mb_strtoupper(mb_substr($s, 0, 1)))
            ->implode('');
    }

    public function waLink(): ?string
    {
        if (! $this->phone) {
            return null;
        }
        $number = preg_replace('/\D/', '', $this->phone);
        if (str_starts_with($number, '0')) {
            $number = '62'.substr($number, 1);
        }

        return 'https://wa.me/'.$number;
    }
}
