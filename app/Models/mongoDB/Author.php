<?php

namespace App\Models\mongoDB;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
    use SoftDeletes;
    protected $table = 'authors';
    protected $connection = 'mongodb';
    protected $primaryKey = '_id';

    public $timestamps = true;

    protected $guarded = [];

    public function getRouteKeyName()
    {
        return '_id';
    }
}
