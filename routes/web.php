<?php

use App\Http\Controllers\api;
use App\Http\Controllers\code;
use App\Http\Controllers\Index;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get("/code", code::class);
Route::any("/", Index::class);
Route::get("/api", api::class);
Route::any("/card", [code::class, "card"]);
