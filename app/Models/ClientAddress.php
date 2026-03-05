<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientAddress extends Model
{
    protected $fillable = [
        'id_client',
        'alias',
        'type',
        'address_details',
        'google_maps_url'
    ];
}
