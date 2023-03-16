<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use Illuminate\Http\Request;
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

Route::group([
    "prefix" => "auth"
], function() {
    Route::post("/login", [AuthController::class, "login"]);
    Route::get("/show", [AuthController::class, "show"])->middleware(["auth:sanctum"]);
    Route::delete("/logout", [AuthController::class, "logout"])->middleware(["auth:sanctum"]);
});

Route::group([
    "prefix" => "admin",
    "middleware" => ["auth:sanctum", "abilities:Admin"]
], function() {
    Route::group([
        "prefix" => "event"
    ], function() {
        Route::get("/", [EventController::class, "index"]);
        Route::get("/{slug}/show", [EventController::class, "show"]);
        Route::post("/store", [EventController::class, "store"]);
        Route::put("/{slug}/update", [EventController::class, "update"]);
        Route::delete("/{slug}/destroy", [EventController::class, "destroy"]);
    });
});
