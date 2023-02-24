<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\Store;
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
        $orders = Order::all();
        return response()->json($orders, 201);
    }

    /**
     * Display a listing of the resource by driver.
     */
    public function indexByDriver(Request $request): JsonResponse
    {
        $driver = $request->driver;
        $orders = $driver->orders()->with('driver')->get();
        return response()->json($orders, 201);
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
        $order = Order::with('driver')->findOrFail($order->getKey());

        return response()->json([
            'order' => $order,
        ]);
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
}