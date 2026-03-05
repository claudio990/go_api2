<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Pay
 *
 * @property $id
 * @property $id_client
 * @property $id_week
 * @property $pay
 * @property $concept
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Pay extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['id_client', 'id_week','id_box', 'pay', 'concept'];


}
