<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PacksController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/getAll', [PacksController::class, 'index']);
Route::post('/create', [PacksController::class, 'create']);
Route::get('/edit/{id}', [PacksController::class, 'edit']);
Route::post('/update', [PacksController::class, 'update']);
Route::delete('/delete/{id}', [PacksController::class, 'delete']);
Route::post('/order', [PacksController::class, 'order']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
