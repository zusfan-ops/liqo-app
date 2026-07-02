<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceEntry extends Model
{
    protected $fillable = ['group_id', 'date', 'type', 'category', 'amount', 'note', 'user_id'];

    protected function casts(): array
    {
        return ['date' => 'date', 'amount' => 'integer'];
    }

    public static function balance(int $groupId): int
    {
        return (int) static::where('group_id', $groupId)
            ->selectRaw("COALESCE(SUM(CASE WHEN type = 'masuk' THEN amount ELSE -amount END), 0) AS saldo")
            ->value('saldo');
    }
}
