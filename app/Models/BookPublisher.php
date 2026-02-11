<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookPublisher extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'publisher_id',
        'published_year',
    ];

    protected $casts = [
        'published_year' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }
}
