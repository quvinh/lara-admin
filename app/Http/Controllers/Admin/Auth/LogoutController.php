<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class LogoutController extends Controller
{
    public static function Routes()
    {
        Route::get('/logout', [LogoutController::class, 'logout'])->name('admin.logout');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }
}
