<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Model;

class CastMaster extends Model
{
    protected $table = 'cast_master';
    protected $fillable = ['cast_name', 'image', 'description','status'];

    protected $casts = [
        'release_year' => 'integer',
        'status' => 'boolean',
    ];

    public function contents()
    {
        return $this->belongsToMany(ContentMaster::class, 'content_cast', 'cast_id', 'content_id');
    }
}

