<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lyrics extends Model
{
    use HasFactory;

    public function artist()
    {
        return $this->belongsToMany(Artist::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function videos()
    {
        return $this->belongsToMany(Video::class);
    }
    public function songs()
    {
        return $this->belongsToMany(Song::class);
    }
}
