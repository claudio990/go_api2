<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WeekClientSell
 *
 * @property $id
 * @property $id_client
 * @property $id_week
 * @property $payed
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class WeekClientSell extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['id_client', 'id_week', 'payed'];


}
