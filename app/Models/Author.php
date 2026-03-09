<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Book;

class Author extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'bio',
        'api_id',
    ];

    public function books()
    {
        return $this->belongsToMany(Book::class, 'book_authors');
    }

    public function sitemap()
    {
        return $this->hasOne(Sitemap::class, 'entity_id')->where('entity', 'authors');
    }

    public function resolveRouteBinding($value, $field = null)
    {
        if (is_numeric($value)) {
            return $this->findOrFail($value);
        }

        return $this->whereHas('sitemap', function ($query) use ($value) {
            $query->where('slug', $value);
        })->firstOrFail();
    }
}
