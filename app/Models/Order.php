<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Services\DriverMatcherService;

/**
 * @property float $origin_lat
 * @property float $origin_lng
 * @property float $dest_lat
 * @property float $dest_lng
 * @property int   $created_at
 * @property int   $updated_at
 * @property int   $assigned_at
 * @property int   $delivered_at
 * @property string $status
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
        'updated_at',
        'assigned_at',
        'delivered_at',
        'status'
    ];

    // define constants for status
    const STATUS_CREATED = 'Created';
    const STATUS_ASSIGNED = 'Assigned';
    const STATUS_DELIVERED = 'Delivered';


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
        'origin_lat' => 'string',
        'origin_lng' => 'string',
        'dest_lat' => 'string',
        'dest_lng' => 'string',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'assigned_at' => 'timestamp',
        'delivered_at' => 'timestamp'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'assigned_at',
        'delivered_at'
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
            $driver = $driverMatcher->matchOrderToDriver($order, $order->store);
            if ($driver) {
                $order->driver()->associate($driver);
                $order->status = Order::STATUS_ASSIGNED;
                $order->assigned_at = now();
                $order->save();
            }
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