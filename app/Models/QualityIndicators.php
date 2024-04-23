<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityIndicators extends Model
{
    use HasFactory;

    protected $fillable = [
        'garment_design_id',
        'creativity',
        'originality',
        'texture',
        'stylistics',
        'functionality',
        'feasibility',
        'feedback'
    ];

    public function garmentDesign()
    {
        return $this->belongsTo(GarmentDesign::class);
    }
}
