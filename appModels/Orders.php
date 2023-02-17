<?php

namespace appModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property float $origin_lat
 * @property float $origin_lng
 * @property float $dest_lat
 * @property float $dest_lng
 * @property int   $created_at
 * @property int   $updated_at
 */
class Orders extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    use HasFactory;
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
        'origin_lat',
        'origin_lng',
        'dest_lat',
        'dest_lng',
        'driver_id',
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