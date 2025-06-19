<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentCast extends Model
{
    protected $table = 'content_cast';
    protected $fillable = ['content_id', 'cast_id'];
}

