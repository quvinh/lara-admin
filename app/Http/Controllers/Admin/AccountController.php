<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    public static function Routes()
    {
        Route::group(['prefix' => 'account'], function () {
            Route::get('/', [AccountController::class, 'index'])->name('admin.account')->middleware(['can:acc.view']);
            Route::group(['middleware' => ['can:acc.edit']], function () {
                Route::get('/create', [AccountController::class, 'create'])->name('admin.account.create');
                Route::post('/store', [AccountController::class, 'store'])->name('admin.account.store');
                Route::put('/edit/{id}', [AccountController::class, 'edit'])->name('admin.account.edit');
                Route::get('/show/{id}', [AccountController::class, 'show'])->name('admin.account.show');
            });
            Route::group(['middleware' => ['can:acc.delete']], function () {
                Route::get('/destroy/{id}', [AccountController::class, 'destroy'])->name('admin.account.destroy');
            });
        });
        Route::group(['prefix' => 'profile'], function () {
            Route::get('/', [AccountController::class, 'profile'])->name('admin.profile');
            Route::put('update/{id}', [AccountController::class, 'update'])->name('admin.profile.update');
            Route::put('update-password/{id}', [AccountController::class, 'updatePass'])->name(('admin.profile.update-pass'));
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts = User::orderByDesc('id')->get();
        return view('admin.components.account.manaccount', compact('accounts'));
    }

    public function profile()
    {
        $profile = User::find(Auth::user()->id);
        return view('admin.components.account.manprofile', compact('profile'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::orderByDesc('id')->get();
        // dd($account);
        return view('admin.components.account.addaccount', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required||unique:users',
            'password' => 'required',
            'email' => 'required||unique:users',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Nhập thiếu thông tin!');
        }
        $gender = ($request->male == '1') ? '1' : '0';
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'birthday' => $request->birthday,
            'mobile' => $request->mobile,
            'image' => $request->image,
            'password' => Hash::make($request->password),
            'username' => $request->username,
            'gender' => $gender,
            'address' => $request->address,
        ]);
        return redirect()->back()->with('success', 'Tạo mới tài khoản thành công');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $account = User::find($id);
        $roles = Role::all();
        return view('admin.components.account.editaccount', compact('account', 'roles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'username' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $users = User::where('id', '<>', $id)->where('email', $request->email)->count();
        if ($users > 0) {
            return redirect()->back()->withErrors(['error' => 'Email already exists']);
        }
        DB::table('model_has_roles')->where('model_id', $id)->delete();
        $user = User::find($id);
        $user->assignRole($request->role);
        $image = '';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name_file = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            if (strcasecmp($extension, 'jpg') === 0 || strcasecmp($extension, 'jepg') === 0 || strcasecmp($extension, 'png') === 0) {
                $name = Str::random(5) . '_' . $name_file;
                while (file_exists('admins/images/account/' . $name)) {
                    $name = Str::random(5) . '_' . $name_file;
                }
                $file->move('admins/images/account/', $name);
                $image = 'admins/images/account/' . $name;
            }
            if (file_exists($user->image)) {
                File::delete($user->image);
            }
        } else {
            $image = $user->image;
        }

        $gender = $request->male == '1' ? '1' : $request->female;
        User::find($request->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'birthday' => $request->birthday,
            'mobile' => $request->mobile,
            'image' => $image,
            'username' => $request->username,
            'gender' => $gender,
            'address' => $request->address,
        ]);
        return redirect()->route('admin.account')->with(['success' => 'Updated successfully']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'username' => 'required',
            'address' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $users = User::where('id', '<>', $id)->where('email', $request->email)->count();
        if ($users > 0) {
            return redirect()->back()->withErrors(['error' => 'Email already exists']);
        }
        $user = User::find($id);
        $image = '';
        // $gender = $request->male == '1' ? '1' : $request->female;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name_file = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            if (strcasecmp($extension, 'jpg') === 0 || strcasecmp($extension, 'jepg') === 0 || strcasecmp($extension, 'png') === 0) {
                $name = Str::random(5) . '_' . $name_file;
                while (file_exists('admins/images/account/' . $name)) {
                    $name = Str::random(5) . '_' . $name_file;
                }
                $file->move('admins/images/account/', $name);
                $image = 'admins/images/account/' . $name;
            }
            if (file_exists($user->image)) {
                File::delete($user->image);
            }
        } else {
            $image = $user->image;
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'birthday' => $request->birthday,
            'mobile' => $request->mobile,
            'image' => $image,
            'username' => $request->username,
            'gender' => $request->gender,
            'address' => $request->address,
        ]);
        return redirect()->route('admin.profile')->with(['success' => 'Updated successfully']);
    }

    public function updatePass(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $user = User::find($id);
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors('Mật khẩu không trùng khớp mời nhập lại!! ');
        } else {
            User::find($id)->update([
                'password' => Hash::make($request->new_password),
            ]);
        }
        return redirect()->route('admin.profile')->with(['success' => 'Updated successfully']);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        DB::table('model_has_roles')->where('model_id', $id)->delete();
        return redirect()->back()->with(['success' => 'Deleted successfully']);
    }
}
