<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = [
        'id_client',
        'id_week',
        'id_address',
        'delivery_type',
        'delivery_day',
        'notes',
        'delivery_status',
        'payment_status',
        'tracking_number'
    ];
}
