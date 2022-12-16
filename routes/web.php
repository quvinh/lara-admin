<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\LogoutController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\LocalizationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return redirect()->route('admin');
});
LocalizationController::Routes();
LoginController::Routes();
Route::group(['middleware' => ['auth']], function () {
    LogoutController::Routes();
    DashboardController::Routes();
    CalendarController::Routes();
    AccountController::Routes();
    RoleController::Routes();
    InvoiceController::Routes();
});

Route::fallback(function () {
    // abort(404, 'API resource not found');
    return redirect()->route('admin');
});
