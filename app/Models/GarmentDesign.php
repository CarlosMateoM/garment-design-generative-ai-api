<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Type\Integer;

class GarmentDesign extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'url',
        'prompt',
        'revised_prompt',    
    ];

    public function qualityIndicators()
    {
        return $this->hasOne(QualityIndicators::class);
    }

}
