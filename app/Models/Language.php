<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Language extends Model
{
    protected $fillable = [
        'name', 'code', 'flag',
    ];

    public function getNameAttribute($value): string
    {
        return ucfirst($value);
    }

    public function dictionaries(): HasMany
    {
        return $this->hasMany(Dictionary::class);
    }
}
