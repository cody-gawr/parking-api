<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $address
 * @property float $latitude
 * @property float $longitude
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\ParkingFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parking query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parking whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parking whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parking whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parking whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parking whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
