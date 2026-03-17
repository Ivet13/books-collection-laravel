<?php

namespace App\Models\mongoDB;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use SoftDeletes;
    protected $table = 'images';
    protected $connection = 'mongodb';
    protected $primaryKey = '_id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;

    protected $guarded = [];

    public function getRouteKeyName()
    {
        return '_id';
    }
}
