<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public static function Routes()
    {
        Route::group(['prefix' => 'company'], function () {
            Route::get('/', [CompanyController::class, 'index'])->name('admin.company')->middleware(['can:com.view']);
            Route::group(['middleware' => ['can:com.edit']], function () {
                Route::get('/create', [CompanyController::class, 'create'])->name('admin.company.create');
                Route::post('/store', [CompanyController::class, 'store'])->name('admin.company.store');
                Route::put('/update/{id}', [CompanyController::class, 'update'])->name('admin.company.update');
                Route::get('/edit/{id}', [CompanyController::class, 'edit'])->name('admin.company.edit');
                Route::get('/invoice/{id}', [CompanyController::class, 'invoice'])->name('admin.company.invoice');

                Route::post('/export', [CompanyController::class, 'export'])->name('admin.company.export');
            });
            Route::group(['middleware' => ['can:com.delete']], function () {
                Route::get('/destroy/{id}', [CompanyController::class, 'destroy'])->name('admin.company.destroy');
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
        $companies = Company::orderByDesc('id')->get();
        return view('admin.components.company.mancompany', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.components.company.addcompany');
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
            'taxcode' => 'required|unique:companies',
            'code' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors(['error' => 'Nhập thiếu thông tin!']);
        }
        Company::create([
            'name' => $request->name,
            'taxcode' => $request->taxcode,
            'code' => $request->code,
            'token' => $request->token,
            'address' => $request->address,
            'mobile' => $request->mobile,
            'manager' => $request->manager,
            'role' => $request->role,
        ]);
        return redirect()->back()->with('success', 'Tạo mới công ty thành công');
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
        $company = Company::find($id);
        return view('admin.components.company.editcompany', compact('company'));
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
            'taxcode' => 'required',
            'code' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Nhập thiếu thông tin!');
        }
        Company::find($id)->update([
            'name' => $request->name,
            'taxcode' => $request->taxcode,
            'code' => $request->code,
            'token' => $request->token,
            'address' => $request->address,
            'mobile' => $request->mobile,
            'manager' => $request->manager,
            'role' => $request->role,
        ]);
        return redirect()->route('admin.company')->with('success', 'Cập nhật thành công');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Company::find($id)->delete();
        return redirect()->back()->with(['success' => 'Deleted successfully']);
    }

    public function invoice(Request $request, $id)
    {
        set_time_limit(0);
        $invoices = array();
        $company = Company::find($id);
        $type = $request->type;
        if ($company->token == '' || $company->code == '') {
            return redirect()->route('admin.company')->withErrors(['error' => 'Token or Code invalid']);
        }
        try {
            if ($type) {
                $url = 'https://hoadondientu.gdt.gov.vn:30000/query/invoices/' . $type; // purchase or sold
                $params = array(
                    'sort' => 'tdlap:desc,khmshdon:asc,shdon:desc',
                    'size' => '15',
                    'search' => 'tdlap=ge=' . date('d/m/Y', strtotime($request->start)) . 'T00:00:00;tdlap=le=' . date('d/m/Y', strtotime($request->end)) . 'T23:59:59;ttxly==5',
                );
                $authorization = 'Authorization: Bearer ' . $company->token;
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0);
                curl_setopt($curl, CURLOPT_TIMEOUT, 50);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                curl_setopt($curl, CURLOPT_URL, $url . '?' . http_build_query($params));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $res = curl_exec($curl);
                $data = json_decode($res, true);
                curl_close($curl);
                try {
                    foreach ($data['datas'] as $key => $value) {
                        $nbmst = $type == 'sold' ? $value['nbmst'] : $value['nmmst'];
                        $khhdon = $value['khhdon'];
                        $shdon = $value['shdon'];
                        $khmshdon = $value['khmshdon'];
                        $url2 = 'https://hoadondientu.gdt.gov.vn:30000/query/invoices/detail';
                        $params2 = array(
                            'nbmst' => $nbmst,
                            'khhdon' => $khhdon,
                            'shdon' => $shdon,
                            'khmshdon' => $khmshdon,
                        );
                        $curl2 = curl_init($url2);
                        curl_setopt($curl2, CURLOPT_CONNECTTIMEOUT, 0);
                        curl_setopt($curl2, CURLOPT_TIMEOUT, 50);
                        curl_setopt($curl2, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                        curl_setopt($curl2, CURLOPT_URL, $url2 . '?' . http_build_query($params2));
                        curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true);
                        $res2 = curl_exec($curl2);
                        $data2 = json_decode($res2, true);
                        curl_close($curl2);
                        try {
                            foreach ($data2['hdhhdvu'] as $index => $item) {
                                array_push($invoices, array(
                                    'shdon' => $value['shdon'],
                                    'khhdon' => $value['khhdon'],
                                    'khmshdon' => $value['khmshdon'],
                                    'nbmst' => $value['nbmst'],
                                    'nbten' => $value['nbten'],
                                    'nmmst' => $value['nmmst'],
                                    'nmten' => $value['nmten'],
                                    'dgia' => $item['dgia'],
                                    'dvtinh' => $item['dvtinh'],
                                    'sluong' => $item['sluong'],
                                    'ten' => $item['ten'],
                                    'thtien' => $item['thtien'],
                                ));
                            }
                        } catch (\Throwable $th) {
                            array_push($invoices, array(
                                'shdon' => $value['shdon'],
                                'khhdon' => $value['khhdon'],
                                'khmshdon' => $value['khmshdon'],
                                'nbmst' => $value['nbmst'],
                                'nbten' => $value['nbten'],
                                'nmmst' => $value['nmmst'],
                                'nmten' => $value['nmten'],
                                'dgia' => '',
                                'dvtinh' => '',
                                'sluong' => '',
                                'ten' => '',
                                'thtien' => '',
                            ));
                        }
                    }
                } catch (\Throwable $th) {
                    array_push($invoices, array(
                        'shdon' => $value['shdon'],
                        'khhdon' => $value['khhdon'],
                        'khmshdon' => $value['khmshdon'],
                        'nbmst' => $value['nbmst'],
                        'nbten' => $value['nbten'],
                        'nmmst' => $value['nmmst'],
                        'nmten' => $value['nmten'],
                        'dgia' => '',
                        'dvtinh' => '',
                        'sluong' => '',
                        'ten' => '',
                        'thtien' => '',
                    ));
                }
            }    //code...
        } catch (\Throwable $th) {
            return redirect()->route('admin.company')->withErrors(['error' => 'Kết nối với TCT thất bại']);
        }

        return view('admin.components.company.datainvoice', compact('company', 'invoices'));
    }

    public function export(Request $request)
    {
        dd($request->all());
    }
}
