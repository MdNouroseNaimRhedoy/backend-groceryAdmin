<?php

use App\Http\Controllers\ProductController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', function (Request $request) {

    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->get('email'))->first();

    if (!$user || !Hash::check($request->get('password'), $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    $device = substr($request->userAgent() ?? '', 0, 255);

    return response()->json([
        'access_token' => $user->createToken($device)->plainTextToken,
        'user' => $user,
    ], Response::HTTP_CREATED);
});


Route::post('logout',function (Request $request) {
    $request->user()->currentAccessToken()->delete();
    return response()->json([
        'message' => 'Logged out',
    ]);
})->middleware('auth:sanctum');


Route::apiResource('products', ProductController::class)->middleware('auth:sanctum');

