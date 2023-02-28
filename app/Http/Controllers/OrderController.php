<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\Store;
use Carbon\Carbon;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $orders = Order::with("store")->with("driver")->get();
        return response()->json($orders, 200);
    }

    /**
     * Display a listing of the resource by driver.
     */
    public function indexByDriver(Request $request): JsonResponse
    {
        $driver = $request->driver;
        $orders = $driver->orders()->with('driver')->where("status", Order::STATUS_ASSIGNED)->get();
        return response()->json($orders, 200);
    }

    /**
     * Store a newly created resource in storage.
     * @param OrderRequest $request
     * @param Store $store
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(OrderRequest $request, Store $store): JsonResponse|ValidationException
    {
        // Create a new order with the validated data
        $order = new Order($request->validated());

        // Associate the order with the specified store
        $order->store()->associate($store);

        $order->save();

        return response()->json($order, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): JsonResponse
    {
        $order = Order::with('driver')->with('store')->findOrFail($order->getKey());

        return response()->json([
            'order' => $order,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        //
    }

    /**
     * Deliver an specific order
     * @param Order $order
     */
    public function deliver(Order $order): JsonResponse
    {
        // get the order status
        $order = Order::findOrFail($order->getKey());
        // validate if the order is assigned
        if ($order->status != Order::STATUS_ASSIGNED) {
            return response()->json([
                'error' => 'The order is not assigned',
            ], 400);
        }
        //validate if the order is already delivered
        if ($order->status == Order::STATUS_DELIVERED) {
            return response()->json([
                'error' => 'The order is already delivered',
            ], 400);
        }
        // update the order status and delivered_at
        $order->status = Order::STATUS_DELIVERED;
        // set the delivered_at date to now (current time)
        $order->delivered_at = Carbon::now();
        $order->save();

        // update the driver orders count
        $driver = $order->driver;
        $driver->orders_count--;
        $driver->update([
            'orders_count' => $driver->orders_count,
        ]);

        return response()->json($order, 201);
    }
}