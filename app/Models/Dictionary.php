<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dictionary extends Model
{
    use HasFactory;

    protected $fillable = [
        'lang_text',
        'translation',
        'status',
        'amount_right',
        'amount_fail',
        'status_card',
        'example',
        'comment',
        'image',
        'user_id',
        'language_id',
    ];

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
