<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class UserPresence extends Model
{
    use HasFactory;

    protected $table='user_presence';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'time_slot_id',
        'user_id',
        'day',
        'present'
    ];

        /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'day' => 'date',
        'present' => 'boolean',
    ];

    public function timeSlot(): BelongsTo
    {
        return $this->belongsTo(TimeSlot::class,'time_slot_id','id');
    }

    public function detections(): HasMany
    {
        return $this->hasMany(Detection::class, 'user_presence_id','id');
    }

}
