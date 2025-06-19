<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentMaster extends Model
{
    protected $table = 'content_master';
    protected $fillable = [
        'movie_name', 'genre_id', 'thumbnail', 'trailer_url', 'release_year',
        'description', 'language', 'duration', 'content_rating', 'full_video','status'
    ];

    public function genre()
    {
        return $this->belongsTo(GenreMaster::class, 'genre_id');
    }

    // ✅ RENAMED to avoid conflict with $casts property
    
    public function castMembers()
    {
        return $this->belongsToMany(CastMaster::class, 'content_cast', 'content_id', 'cast_id');
    }

}

