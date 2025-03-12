<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySensorStats extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'sensor_id',
        'consumo_progressivo_giornaliero',
        'spreco_giornaliero',
        'eccesso_giornaliero',
        'risparmio_giornaliero_da_spreco',
        'risparmio_giornaliero_da_eccesso',
        'consumo_giornaliero',
        'day_'
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'day_' => 'date',
        'consumo_progressivo_giornaliero' =>'float',
        'spreco_giornaliero' =>'float',
        'eccesso_giornaliero' =>'float',
        'risparmio_giornaliero_da_spreco' =>'float',
        'risparmio_giornaliero_da_eccesso' =>'float',
        'consumo_giornaliero'=>'float'
    ];
}
