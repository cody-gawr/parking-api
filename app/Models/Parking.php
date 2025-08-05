<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parking extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'parkings';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'latitude'  => 'float',
        'longitude' => 'float',
    ];
}
