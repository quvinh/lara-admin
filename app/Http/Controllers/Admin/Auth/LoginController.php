<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('guest')->except('logout');
    // }

    public static function Routes()
    {
        Route::match(['get', 'post'], '/login', [LoginController::class, 'login'])->name('admin.login');
    }

    public function login(Request $request)
    {
        if ($request->getMethod() == 'GET') {
            return view('admin.pages.login');
        }

        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);
        if($validator->fails()) {
            return redirect()->back()->with(['error' => 'Login failed']);
        }
        $credentials = $request->only(['username', 'password']);
        if (auth()->attempt($credentials, $request->remember_me == 'on')) {
            $user = auth()->user();
            Log::alert('[LOGIN] by ' . $user->name . '{"id":' . $user->id . ', "username":"' . $user->username . '", "status":"success"}');
            return redirect()->route('admin');
        } else {
            return back()->with(['error' => 'Your username and password are wrong']);
        }
    }
}
