<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DictionaryTrainingHistory extends Model
{
    protected $fillable = [
        'user_id',
        'amount_right',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function today(): ?self
    {
        return $this->whereDate('created_at', now()->toDateString())
            ->where('user_id', auth()->id())->first();
    }
}
