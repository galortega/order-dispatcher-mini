<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\JsonResponse;

class StoreController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $Stores = Store::all();
        return response()->json($Stores, 201);
    }
}