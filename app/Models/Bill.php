<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Bill
 *
 * @property $id
 * @property $bill
 * @property $concept
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Bill extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['id_box','bill', 'concept'];


}
