<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    use HasFactory;

    protected $fillable = ['keyword'];

    public function keywordCategory()
    {
        return $this->belongsToMany(KeywordsCategory::class,'keywords_categories_keywords');
    }
}
