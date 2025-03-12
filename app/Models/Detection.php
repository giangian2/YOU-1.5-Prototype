<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Detection extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'apower1',
        'apower2',
        'aenergy1',
        'aenergy2',        
        'sensor_id',
        'user_presence_id'
    ];


    public function userPresence(): BelongsTo
    {
        return $this->belongsTo(UserPresence::class, 'user_presence_id', 'id');
    }



    public function sensor(): BelongsTo
    {
        return $this->belongsTo(Sensor::class, 'sensor_id', 'id');
    }


}
