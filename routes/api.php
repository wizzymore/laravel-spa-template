<?php

use App\Http\Helpers\Api;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return response()->json([
        'test' => 1
    ]);
})->middleware('auth');

Route::get('/login', function () {
    $data = request()->all();

    $token = Auth::attempt($data);

    if (!$token) {
        return Api::error('Invalid credentials');
    }

    return Api::success([
        'token' => $token->toString()
    ]);
})->middleware('guest');
