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
        $invoices = array();
        $company = Company::find($id);
        $type = $request->type;
        if ($company->token == '' || $company->code == '') {
            return redirect()->route('admin.company')->withErrors(['error' => 'Token or Code invalid']);
        }
        try {
            if ($type) {
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://hoadondientu.gdt.gov.vn:30000/query/invoices/' . $type . '?sort=tdlap:desc,khmshdon:asc,shdon:desc&size=15&search=tdlap=ge=' . date('d/m/Y', strtotime($request->start)) . 'T00:00:00;tdlap=le=' . date('d/m/Y', strtotime($request->end)) . 'T23:59:59;ttxly==5',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Bearer ' . $company->token,
                    ),
                ));

                $response = curl_exec($curl);
                $data = json_decode($response, true);
                curl_close($curl);
                try {
                    foreach ($data['datas'] as $key => $value) {
                        $nbmst = $value['nbmst'];
                        $khhdon = $value['khhdon'];
                        $shdon = $value['shdon'];
                        $khmshdon = $value['khmshdon'];

                        $curl2 = curl_init();

                        curl_setopt_array($curl2, array(
                            CURLOPT_URL => 'https://hoadondientu.gdt.gov.vn:30000/query/invoices/detail?nbmst=' . $nbmst . '&khhdon=' . $khhdon . '&shdon=' . $shdon . '&khmshdon=' . $khmshdon,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'GET',
                            CURLOPT_HTTPHEADER => array(
                                'Authorization: Bearer ' . $company->token,
                            ),
                        ));

                        $response2 = curl_exec($curl2);
                        $data2 = json_decode($response2, true);
                        curl_close($curl2);
                        // dd('https://hoadondientu.gdt.gov.vn:30000/query/invoices/detail?nbmst=' . $nbmst . '&khhdon=' . $khhdon . '&shdon=' . $shdon . '&khmshdon=' . $khmshdon, $data2);
                        try {
                            foreach ($data2['hdhhdvu'] as $index => $item) {
                                array_push($invoices, array(
                                    'shdon' => $value['shdon'],
                                    'khhdon' => $value['khhdon'],
                                    'khmshdon' => $value['khmshdon'],
                                    'nbdchi' => $value['nbdchi'],
                                    'nbmst' => $value['nbmst'],
                                    'htttoan' => $value['htttoan'],
                                    'nbten' => $value['nbten'],
                                    'nmmst' => $value['nmmst'],
                                    'nmdchi' => $value['nmdchi'],
                                    'nmten' => $value['nmten'],
                                    'ntao' => $value['ntao'],
                                    'ntnhan' => $value['ntnhan'],
                                    'tchathd' => $value['tchat'],
                                    'tthai' => $value['tthai'],
                                    'ttxly' => $value['ttxly'],
                                    'dgia' => $item['dgia'],
                                    'dvtinh' => $item['dvtinh'],
                                    'sluong' => $item['sluong'],
                                    'stckhau' => $item['stckhau'] == null ? 0 : $item['stckhau'],
                                    'tchat' => $item['tchat'],
                                    'ten' => $item['ten'],
                                    'thtien' => $item['thtien'],
                                    'tsuat' => $item['tsuat'] == null ? 0 : $item['tsuat'],
                                ));
                            }
                        } catch (\Throwable $th) {
                            array_push($invoices, array(
                                'shdon' => $value['shdon'],
                                'khhdon' => $value['khhdon'],
                                'khmshdon' => $value['khmshdon'],
                                'nbdchi' => $value['nbdchi'],
                                'nbmst' => $value['nbmst'],
                                'nbten' => $value['nbten'],
                                'htttoan' => $value['htttoan'],
                                'nmdchi' => $value['nmdchi'],
                                'nmmst' => $value['nmmst'],
                                'nmten' => $value['nmten'],
                                'ntao' => $value['ntao'],
                                'ntnhan' => $value['ntnhan'],
                                'tchathd' => $value['tchat'],
                                'tthai' => $value['tthai'],
                                'ttxly' => $value['ttxly'],
                                'dgia' => '',
                                'dvtinh' => '',
                                'sluong' => '',
                                'stckhau' => 0,
                                'tchat' => '',
                                'ten' => '',
                                'thtien' => '',
                                'tsuat' => 0,
                            ));
                        }
                    }
                } catch (\Throwable $th) {
                    array_push($invoices, array(
                        'shdon' => $value['shdon'],
                        'khhdon' => $value['khhdon'],
                        'khmshdon' => $value['khmshdon'],
                        'nbdchi' => $value['nbdchi'],
                        'nbmst' => $value['nbmst'],
                        'nbten' => $value['nbten'],
                        'htttoan' => $value['htttoan'],
                        'nmdchi' => $value['nmdchi'],
                        'nmmst' => $value['nmmst'],
                        'nmten' => $value['nmten'],
                        'ntao' => $value['ntao'],
                        'ntnhan' => $value['ntnhan'],
                        'tchathd' => $value['tchat'],
                        'tthai' => $value['tthai'],
                        'ttxly' => $value['ttxly'],
                        'dgia' => '',
                        'dvtinh' => '',
                        'sluong' => '',
                        'stckhau' => 0,
                        'tchat' => '',
                        'ten' => '',
                        'thtien' => '',
                        'tsuat' => 0,
                    ));
                }
            }
        } catch (\Throwable $th) {
            return redirect()->route('admin.company')->withErrors(['error' => 'Kết nối với TCT thất bại.']);
        }

        return view('admin.components.company.datainvoice', compact('company', 'invoices'));
    }

    public function export(Request $request)
    {
        dd($request->all());
    }
}
