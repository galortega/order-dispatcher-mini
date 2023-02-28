<?php

namespace App\Services;

use App\Models\Driver;
use App\Models\Order;
use App\Models\Store;
use Http;

class DriverMatcherService
{

    /**
     * Match an order to a driver using the store's id
     * @param Order $order
     * @param Store $store
     * @return Driver|null
     */
    public function matchOrderToDriver(Order $order, Store $store): ?Driver
    {
        if (!$store) {
            return null; // no store found
        }
        // Determine which matching algorithm to use based on the store_id
        switch ($store->getKey()) {
            case 1:
                return $this->matchOrderToDriverWithGoogleMaps($order, $store);
            case 2:
                return $this->matchOrderToDriverWithDriverAvailability($order);
            case 3:
                return $this->matchOrderToDriverWithDriverDistance($order);
            default:
                return null;
        }
    }

    /**
     * Match an order to the more available driver
     * @param Order $order
     * @return Driver|null
     */
    private function matchOrderToDriverWithDriverAvailability(Order $order): ?Driver
    {
        $availableDrivers = Driver::whereColumn('orders_count', '<', 'max_orders')
            ->orderBy('orders_count', 'asc')
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

    /**
     * Match an order to the closest available driver
     * @param Order $order
     * @return Driver|null
     */
    private function matchOrderToDriverWithDriverDistance(Order $order): ?Driver
    {
        $availableDrivers = Driver::whereColumn('orders_count', '<', 'max_orders')
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

    /**
     * Match a driver to the closest available order using Google Maps API
     * @param Driver $driver
     * @param Store $store
     * @return Order|null
     */
    private function matchOrderToDriverWithGoogleMaps(Order $order, Store $store): ?Driver
    {
        $availableDrivers = Driver::whereColumn('orders_count', '<', 'max_orders')
            ->get();

        if ($availableDrivers->isEmpty()) {
            return null; // no available drivers
        }

        $closestDriver = null;
        $minDistance = INF;

        foreach ($availableDrivers as $driver) {

            $distanceMatrix = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json', [
                'origins' => $driver->latitude . ',' . $driver->longitude,
                'destinations' => $store->latitude . ',' . $store->longitude,
                'key' => env('GOOGLE_MAPS_API_KEY')
            ]);
            $distance = $distanceMatrix['rows'][0]['elements'][0]['distance']['value'];
            if ($distance < $minDistance) {
                $closestDriver = $driver;
                $minDistance = $distance;
            }
        }

        if ($closestDriver) {
            $closestDriver->orders_count++;
            $closestDriver->update([
                'orders_count' => $closestDriver->orders_count
            ]);
        }

        return $closestDriver;
    }


}