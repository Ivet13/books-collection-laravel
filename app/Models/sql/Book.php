<?php

namespace App\Models\sql;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\sql\Author;
use App\Models\sql\Publisher;
use App\Models\sql\Genre;
use App\Models\sql\Customer;

class Book extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'isbn',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function sitemap()
    {
        return $this->hasOne(Sitemap::class, 'entity_id')->where('entity', 'books');
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'book_authors');
    }

    public function genres()
    {
        return $this->belongsToMany(\App\Models\sql\Genre::class, 'book_genres');
    }
    public function bookPublisher()
    {
        return $this->hasOne(\App\Models\sql\BookPublisher::class);
    }

    public function publisher()
    {
        return $this->hasOneThrough(
            Publisher::class,
            BookPublisher::class,
            'book_id',       // FK en book_publishers...
            'id',            // PK en publishers...
            'id',            // PK en books...
            'publisher_id'   // FK a publishers en book_publishers
        );
    }

    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'book_customer', 'book_id', 'customer_id')
            ->withPivot(['status', 'is_favorite', 'rating', 'review'])
            ->withTimestamps();
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
