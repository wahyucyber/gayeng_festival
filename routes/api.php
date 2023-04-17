<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProfilController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\TicketController;
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
    "middleware" => ["throttle:noLimit"]
], function() {
    Route::group([
        "prefix" => "auth"
    ], function() {
        Route::post("/login", [AuthController::class, "login"]);
        Route::post("/register", [AuthController::class, "register"]);
        Route::get("/show", [AuthController::class, "show"])->middleware(["auth:sanctum"]);
        Route::delete("/logout", [AuthController::class, "logout"])->middleware(["auth:sanctum"]);
    });

    Route::group([
        "prefix" => "event"
    ], function() {
        Route::get("/", [EventController::class, "index"]);
        Route::get("/{slug}/show", [EventController::class, "show"]);
    });

    Route::group([
        "prefix" => "news"
    ], function() {
        Route::get("/", [NewsController::class, "index"]);
        Route::get("/{slug}/show", [NewsController::class, "show"]);
    });

    Route::post("/transaction/handleNotification", [OrderController::class, "handleNotificationMidtrans"]);
});

Route::group([
    "prefix" => "admin",
    "middleware" => ["auth:sanctum", "abilities:Admin"]
], function() {
    Route::group([
        "prefix" => "event"
    ], function() {
        Route::get("/", [EventController::class, "index"]);
        Route::get("/select2", [EventController::class, "indexSelect2"]);
        Route::get("/{slug}/show", [EventController::class, "show"]);
        Route::post("/store", [EventController::class, "store"]);
        Route::put("/{slug}/update", [EventController::class, "update"]);
        Route::delete("/{slug}/destroy", [EventController::class, "destroy"]);
    });

    Route::get("/category", [CategoryController::class, "index"])->withoutMiddleware(["auth:sanctum", "abilities:Admin"]);

    Route::group([
        "prefix" => "news"
    ], function() {
        Route::get("/", [NewsController::class, "index"]);
        Route::get("/{slug}/show", [NewsController::class, "show"]);
        Route::post("/store", [NewsController::class, "store"]);
        Route::put("/{slug}/update", [NewsController::class, "update"]);
        Route::delete("/{slug}/destroy", [NewsController::class, "destroy"]);
    });

    Route::group([
        "prefix" => "order"
    ], function() {
        Route::get("/", [OrderController::class, "index"]);
        Route::get("/{invoice}/show", [OrderController::class, "show"])->where("invoice", ".*");
    });

    Route::group([
        "prefix" => "ticket"
    ], function() {
        Route::get("/", [TicketController::class, "index"]);
        Route::post("/confirm", [TicketController::class, "confirmTicket"]);
    });

    Route::group([
        "prefix" => "report",
        "middleware" => ["auth:sanctum", "abilities:Admin"]
    ], function() {
        Route::get("/", [ReportController::class, "index"]);
        Route::get("/totalPay", [ReportController::class, "countTotalPay"]);
    });

    Route::put('/update_profile', [ProfilController::class, "update"]);
});

Route::group([
    "prefix" => "user",
    "middleware" => ["auth:sanctum", "abilities:Customer"]
], function() {
    Route::group([
        "prefix" => "cart"
    ], function() {
        Route::get('/', [CartController::class, "index"]);
        Route::get('/{id}/show', [CartController::class, "show"]);
        Route::post('/store', [CartController::class, "store"]);
        Route::put('/{id}/update', [CartController::class, "update"]);
        Route::delete('/{id}/destroy', [CartController::class, "destroy"]);
    });

    Route::group([
        "prefix" => "order"
    ], function() {
        Route::get("/", [OrderController::class, "index"]);
        Route::get("/{invoice}/show", [OrderController::class, "show"])->where("invoice", ".*");
        Route::post("/store", [OrderController::class, "store"]);
    });

    Route::group([
        "prefix" => "ticket"
    ], function() {
        Route::get("/", [TicketController::class, "index"]);
        Route::get("/{code}/show", [TicketController::class, "show"]);
    });

    Route::put('/update_profile', [ProfilController::class, "update"]);
});
