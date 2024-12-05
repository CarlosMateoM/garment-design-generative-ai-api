<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeywordsCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name'];


    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, 'keywords_categories_keywords');
    }
}
