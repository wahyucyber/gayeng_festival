<?php

use App\Http\Controllers\Admin\DashboardController;
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

Route::get("/login", [AuthController::class, "index"])->name("auth.login");
Route::post("/login_post", [AuthController::class, "login_post"])->name("auth.login_post");
Route::get("/auth_check", [AuthController::class, "auth_check"])->name("auth.check");

Route::group([
    "prefix" => "admin"
], function() {
    Route::get("/", [DashboardController::class, "index"])->name("admin.dashboard");
});
