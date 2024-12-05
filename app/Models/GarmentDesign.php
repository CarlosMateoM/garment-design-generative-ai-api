<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GarmentDesign extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'prompt',
        'revised_prompt',
    ];


    public function images()
    {
        return $this->belongsToMany(Image::class, 'garment_designs_images');
    }


    public function qualityIndicators()
    {
        return $this->hasOne(QualityIndicators::class);
    }
}
