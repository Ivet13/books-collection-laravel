<?php

namespace App\Models\mongoDB;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
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

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'book_authors');
    }

    public function genres()
    {
        return $this->belongsToMany(\App\Models\Genre::class, 'book_genres');
    }
    public function bookPublisher()
    {
        return $this->hasOne(\App\Models\BookPublisher::class);
    }
}
