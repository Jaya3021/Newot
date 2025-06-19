<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'vimeo_uri',
        'vimeo_link',
        'embed_html',
        // 'user_id' // If you add user ownership later
    ];

    // If you add user ownership later, define the relationship:
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }
}
