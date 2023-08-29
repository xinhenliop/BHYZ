<?php

//routes\Admin.php
use App\Http\Controllers\agent;
use App\Http\Controllers\auth\Agents;

Route::namespace('agent')->group(function () {
    Route::any(config('system')['system']['agent_url'] . "/login", [Agents::class, "login"]);
    Route::any(config('system')['system']['agent_url'] . "/logout", [Agents::class, "logout"]);
    Route::group(["middleware" => ['agent']], function () {
        Route::any(config('system')['system']['agent_url'], [agent::class, "index"]);
    });
});

