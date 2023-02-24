<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property string $name
 * @property int    $max_orders
 * @property int    $orders_count
 * @property float  $latitude
 * @property float  $longitude
 * @property int    $created_at
 * @property int    $updated_at
 */
class Driver extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    use HasFactory, HasApiTokens;

    protected $guard = 'driver';

    protected $table = 'drivers';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'max_orders',
        'orders_count',
        'code',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'max_orders' => 'int',
        'orders_count' => 'int',
        'code' => 'int',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = true;

    // Scopes...

    // Functions ...

    // Relations ...
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}