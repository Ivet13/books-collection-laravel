<?php

namespace App\Models\sql;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\sql\Book;

class Customer extends Authenticatable
{
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = [
        'email',
        'password',
        'name',
        'about',
        'deactivated_at',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'deactivated_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    /* ---------- Helpers ---------- */

    public function isActive(): bool
    {
        return is_null($this->deactivated_at);
    }

    /* ---------- Scopes ---------- */

    public function scopeActive(Builder $query)
    {
        return $query->whereNull('deactivated_at');
    }

    public function scopeInactive(Builder $query)
    {
        return $query->whereNotNull('deactivated_at');
    }

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'book_customer', 'customer_id', 'book_id')
            ->withPivot(['status', 'is_favorite', 'rating', 'review'])
            ->withTimestamps();
    }
}
