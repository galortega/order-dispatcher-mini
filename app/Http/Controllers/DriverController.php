<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        // get all drivers
        $drivers = Driver::all();
        return response()->json($drivers, 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): Response
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Driver $driver): JsonResponse
    {
        // find a driver by id
        $driver = Driver::with('orders')->findOrFail($driver->getKey());

        return response()->json([
            'driver' => $driver,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Driver $driver): Response
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Driver $driver): Response
    {
        //
    }
}