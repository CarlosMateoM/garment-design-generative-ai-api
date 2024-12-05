<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'format',
        'alt_text',
    ];


    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, 'images_keywords');
    }
}
