<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GenreMaster extends Model
{
    protected $table = 'genre_master';
    protected $fillable = ['genre_name', 'image', 'description', 'status'];

    public function contents()
    {
        return $this->hasMany(ContentMaster::class, 'genre_id');
    }
}
