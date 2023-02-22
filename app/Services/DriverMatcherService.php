<?php

namespace App\Services;

use App\Models\Driver;
use App\Models\Order;

class DriverMatcherService
{
    /**
     * Match an order to the closest available driver
     * @param Order $order
     * @return Driver|null
     */
    public function matchOrderToDriver(Order $order): ?Driver
    {
        \DB::enableQueryLog();
        $availableDrivers = Driver::where('orders_count', '<', 2)
            ->orderByRaw('SQRT(POWER(latitude - ?, 2) + POWER(longitude - ?, 2))', [$order->origin_lat, $order->origin_lng])
            ->limit(1)
            ->get();

        if ($availableDrivers->isEmpty()) {
            return null; // no available drivers
        }

        $driver = $availableDrivers->first();
        // Increment the driver's orders count
        $driver->orders_count++;
        $driver->update([
            'orders_count' => $driver->orders_count,
        ]);

        return $driver;
    }
} {

}