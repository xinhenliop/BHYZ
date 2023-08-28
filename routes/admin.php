<?php


use App\Http\Controllers\admin;
use App\Http\Controllers\auth\Admins;
use Illuminate\Support\Facades\Route;

Route::any(config('system')['system']['admin_url'] . "/login", [Admins::class, "login"]);
Route::any(config('system')['system']['admin_url'] . "/logout", [Admins::class, "logout"]);
Route::group(["middleware" => ['admin']], function () {
    Route::any(config('system')['system']['admin_url'], [admin::class, 'index'])->name("admin");
});

