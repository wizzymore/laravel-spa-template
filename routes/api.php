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
    return Api::success([
        'message' => 'Welcome to the API'
    ]);
});

// Example of protected route
Route::get('/protected', function () {
    return Api::success([
        'protected' => 'This is a protected route'
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

Route::get('{any}', function () {
    Api::notFound();
})->where('any', '.*');
