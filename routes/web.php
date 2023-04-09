<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\AuthController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::group([
    "middleware" => ["check_auth"]
], function() {
    Route::get("/login", [AuthController::class, "index"])->name("auth.login");
    Route::post("/login_post", [AuthController::class, "login_post"])->name("auth.login_post");
    Route::get("/auth_check", [AuthController::class, "auth_check"])->name("auth.check");
});

Route::delete("/logout", [AuthController::class, "logout"])->name("auth.logout");

Route::group([
    "prefix" => "admin",
    "middleware" => ["guest"]
], function() {
    Route::get("/", [DashboardController::class, "index"])->name("admin.dashboard");

    Route::group([
        "prefix" => "event"
    ], function() {
        Route::get("/", [EventController::class, "index"])->name("admin.event");
        Route::get("/create", [EventController::class, "create"])->name("admin.event.create");
        Route::get("/{slug}/update", [EventController::class, "update"])->name("admin.event.update");
    });

    Route::group([
        "prefix" => "news"
    ], function() {
        Route::get("/", [NewsController::class, "index"])->name("admin.news");
        Route::get("/create", [NewsController::class, "create"])->name("admin.news.create");
        Route::get("/{slug}/update", [NewsController::class, "update"])->name("admin.news.update");
    });

    Route::group([
        "prefix" => "order"
    ], function() {
        Route::get("/", [OrderController::class, "index"])->name("admin.order");
        Route::get("/{invoice}/show", [OrderController::class, "show"])->name("admin.order.show")->where("invoice", ".*");
    });
});
