<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class CalendarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public static function Routes()
    {
        Route::group(['prefix' => 'calendar'], function() {
            Route::get('/', [CalendarController::class, 'index'])->name('admin.calendar');
        });
    }

    public function index()
    {
        return view('admin.components.calendar.calendar');
    }
}
