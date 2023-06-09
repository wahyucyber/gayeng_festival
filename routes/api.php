<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\EventTicketController;
use App\Http\Controllers\Api\EventTicketTypeController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProfilController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\StaffController;
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
    "prefix" => "order"
], function() {
    Route::post("/store", [OrderController::class, "store"]);
    Route::get("/{invoice}/show", [OrderController::class, "show"])->where("invoice", '.*');
});

Route::group([
    "prefix" => "admin",
    "middleware" => ["auth:sanctum", "abilities:Admin"]
], function() {
    Route::get("/dashboard", [DashboardController::class, "index"]);

    Route::group([
        "prefix" => "event"
    ], function() {
        Route::get("/", [EventController::class, "index"]);
        Route::get("/select2", [EventController::class, "indexSelect2"]);
        Route::get("/{slug}/show", [EventController::class, "show"]);
        Route::post("/store", [EventController::class, "store"]);
        Route::put("/{slug}/update", [EventController::class, "update"]);
        Route::delete("/{slug}/destroy", [EventController::class, "destroy"]);

        Route::get("/category", [CategoryController::class, "index"]);

        Route::group([
            "prefix" => "ticket"
        ], function() {
            Route::get("/", [EventTicketController::class, "index"]);
            Route::get("/{id}/show", [EventTicketController::class, "show"]);
            Route::post("/store", [EventTicketController::class, "store"]);
            Route::put("/{id}/update", [EventTicketController::class, "update"]);
            Route::delete("/{id}/destroy", [EventTicketController::class, "destroy"]);

            Route::get("/type", [EventTicketTypeController::class, "index"]);
        });
    });

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

    Route::group([
        "prefix" => "staff"
    ], function() {
        Route::get("/", [StaffController::class, "index"]);
        Route::get("/{id}/show", [StaffController::class, "show"]);
        Route::post('/store', [StaffController::class, "store"]);
        Route::put('/{id}/update', [StaffController::class, "update"]);
        Route::delete("/{id}/destroy", [StaffController::class, "destroy"]);
    });
});

Route::group([
    "prefix" => "staff",
    "middleware" => ["auth:sanctum", "abilities:Staff"]
], function() {
    Route::get("/dashboard", [DashboardController::class, "index"]);

    Route::get("/event/select2", [EventController::class, "indexSelect2"]);

    Route::group([
        "prefix" => "ticket"
    ], function() {
        Route::get("/", [TicketController::class, "index"]);
        Route::post("/confirm", [TicketController::class, "confirmTicket"]);
    });

    Route::put('/update_profile', [ProfilController::class, "update"]);
});
