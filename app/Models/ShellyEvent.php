<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;


class ShellyEvent extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'shelly_events';

    protected $fillable = ['shelly_id','apower','topic','aenergy'];
}
