<?php

namespace App\Models\mongoDB;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
    use SoftDeletes;
    protected $table = 'authors';

    protected $fillable = [
        'name',
        'bio',
        'api_id',
    ];

    public function books()
    {
        return $this->belongsToMany(Book::class, 'book_authors');
    }
}
