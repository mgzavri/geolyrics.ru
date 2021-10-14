<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    use HasFactory;

    protected $table = 'artists';
    protected $fillable = ["alias", "title_ru", "title_ge", "title_en", "desc_ru", "desc_ge", "desc_en"];

    public function lyrics()
    {
        return $this->belongsToMany(Lyrics::class);
    }

    public function photos()
    {
        return $this->belongsToMany(Photo::class);

    }


}
