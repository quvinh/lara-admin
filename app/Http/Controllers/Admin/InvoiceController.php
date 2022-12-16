<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    public static function Routes()
    {
        Route::group(['prefix' => 'invoice'], function () {
            Route::get('/', [InvoiceController::class, 'index'])->name('admin.invoice')->middleware(['can:inv.view']);
            Route::group(['middleware' => ['can:inv.add']], function () {
                Route::get('/add', [InvoiceController::class, 'create'])->name('admin.invoice.add');
                Route::post('/store', [InvoiceController::class, 'store'])->name('admin.invoice.store');
            });
            Route::group(['middleware' => ['can:inv.edit']], function () {
                Route::get('/edit/{id}', [InvoiceController::class, 'edit'])->name('admin.invoice.edit');
                Route::put('/update/{id}', [InvoiceController::class, 'update'])->name('admin.invoice.update');
            });
            Route::group(['middleware' => ['can:inv.delete']], function () {
                Route::get('/delete/{id}', [InvoiceController::class, 'delete'])->name('admin.invoice.delete');
            });
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::orderByDesc('invoice_type')->orderByDesc('invoice_status')->get();
        return view('admin.components.invoice.maninvoice', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.components.invoice.addinvoice');
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
            'invoice_title' => 'required',
            'invoice_detail' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->input());
        }
        $user = Auth::user();
        Invoice::create(array_merge($validator->validated(), [
            'invoice_status' => $request->invoice_status,
            'invoice_type' => $request->invoice_type,
            'created_by' => $user->id,
        ]));
        Log::info('[STORE] [INVOICE] by ' . $user->username . '{"id":' . $user->id . ', "username":"' . $user->username . '", "status":"success"}');
        if ($request->station_action == 'add') {
            return redirect()->route('admin.invoice.add')->with(['success' => 'Added successfully']);
        } else if ($request->station_action == 'list') {
            return redirect()->route('admin.invoice')->with(['success' => 'Added successfully']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoice = Invoice::find($id);
        return view('admin.components.invoice.editinvoice', compact('invoice'));
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
            'invoice_title' => 'required',
            'invoice_detail' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->input());
        }
        $user = Auth::user();
        Invoice::find($id)->update([
            'invoice_title' => $request->invoice_title,
            'invoice_detail' => $request->invoice_detail,
            'invoice_status' => $request->invoice_status,
            'invoice_type' => $request->invoice_type,
        ]);
        Log::info('[UPDATE] [INVOICE] by ' . $user->username . '{"id":' . $user->id . ', "username":"' . $user->username . '", "status":"success"}');
        return redirect()->route('admin.invoice')->with(['success' => 'Updated successfully']);
    }

    public function delete($id)
    {
        Invoice::find($id)->delete();
        $user = Auth::guard('admin')->user();
        Log::info('[DESTROY] [INVOICE] by ' . $user->username . '{"id":' . $user->id . ', "username":"' . $user->username . '", "status":"success"}');
        return redirect()->route('admin.invoice')->with(['success' => 'Deleted successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
