<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WeekSell
 *
 * @property $id
 * @property $name
 * @property $start_date
 * @property $end_date
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class WeekSell extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'start_date', 'end_date'];


}
