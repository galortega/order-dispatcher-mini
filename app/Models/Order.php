<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\DriverMatcherService;

/**
 * @property float $origin_lat
 * @property float $origin_lng
 * @property float $dest_lat
 * @property float $dest_lng
 * @property int   $created_at
 * @property int   $updated_at
 */
class Order extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'orders';

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
        'store_id',
        'driver_id',
        'origin_lat',
        'origin_lng',
        'dest_lat',
        'dest_lng',
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
        'origin_lat' => 'double',
        'origin_lng' => 'double',
        'dest_lat' => 'double',
        'dest_lng' => 'double',
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
    // match order to closest driver
    protected static function booted()
    {
        static::created(function ($order) {
            $driverMatcher = new DriverMatcherService();
            $driver = $driverMatcher->matchOrderToDriver($order);
            if ($driver)
                $order->driver()->associate($driver);
        });
    }
    // Relations ...
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}