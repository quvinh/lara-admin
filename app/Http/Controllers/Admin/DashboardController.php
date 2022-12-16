<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class DashboardController extends Controller
{
    public static function Routes()
    {
        Route::get('/', [DashboardController::class, 'index'])->name('admin');
    }

    public function index()
    {
        return view('admin.home.dashboard');
    }
}
