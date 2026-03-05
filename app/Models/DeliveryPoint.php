<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryPoint extends Model
{
    protected $fillable = [
        'name',
        'google_maps_url'
    ];
}
