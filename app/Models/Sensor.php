<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Sensor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'station_id',
        'key',
        'type'
    ];

    public function detections(): HasMany
    {
        return $this->hasMany(Detection::class, 'sensor_id','id');
    }

    public function station():BelongsTo
    {
        return  $this->belongsTo(Station::class,'station_id','id');
    }
}
