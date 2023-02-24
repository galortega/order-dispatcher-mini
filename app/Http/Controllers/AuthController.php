<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|size:4',
        ]);

        $driver = Driver::where('code', $validatedData['code'])->first();

        if (!$driver) {
            return response()->json(['error' => 'Invalid code'], 401);
        }

        $token = $driver->createToken('auth_token')->plainTextToken;

        // update driver's auth token
        $driver->auth_token = $token;
        $driver->save();

        return response()->json(['access_token' => $token]);
    }
}