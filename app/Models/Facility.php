<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'capacity',
        'status',
        'image_path',
        'features',
    ];
    
    protected $casts = [
        'features' => 'array',
    ];
}
