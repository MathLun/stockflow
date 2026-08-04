<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\ProductController;
Route::get('/products', [
	ProductController::class,
	'index'
]);
Route::post('/products', [
	ProductController::class,
	'store'
]);
Route::put('/products/{id}', [
	ProductController::class,
	'update'
]);
Route::get('/products/{id}', [
	ProductController::class,
	'show'
]);
Route::delete('/products/{id}', [
	ProductController::class,
	'destroy'
]);
