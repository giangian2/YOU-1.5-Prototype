<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Station extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
    ];

    public function sensors(): HasMany
    {
        return $this->hasMany(Sensor::class, 'station_id','id');
    }

    public function users():BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_station', 'station_id', 'user_id');
    }
}
