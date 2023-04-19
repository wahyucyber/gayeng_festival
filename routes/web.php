<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProfilController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use App\Http\Controllers\Staff\ProfileController;
use App\Http\Controllers\Staff\TicketController as StaffTicketController;
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
        Route::get("/{slug}/show", [EventController::class, "show"])->name("admin.event.show");
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
        Route::get("/{invoice}/show", [OrderController::class, "show"])->where("invoice", ".*")->name("admin.order.show");
    });

    Route::group([
        "prefix" => "ticket"
    ], function() {
        Route::get("/", [TicketController::class, "index"])->name("admin.ticket");
        Route::get("/scan", [TicketController::class, "scan"])->name("admin.ticket.scan");
    });

    Route::get("/report", [ReportController::class, "index"])->name("admin.report");

    Route::get("/staff", [StaffController::class, "index"])->name("admin.staff");

    Route::get("/profile", [ProfilController::class, "index"])->name("admin.profile");
});

Route::group([
    "prefix" => "staff",
    "middelware" => ["guest"]
], function() {
    Route::get("/", [StaffDashboardController::class, "index"])->name("staff.dashboard");

    Route::group([
        "prefix" => "staff"
    ], function() {
        Route::get("/", [StaffTicketController::class, "index"])->name("staff.ticket");
        Route::get("/scan", [StaffTicketController::class, 'scan'])->name("staff.ticket.scan");
    });

    Route::get("/profile", [ProfileController::class, "index"])->name("staff.profile");;
});
