@extends('admin.home.master')

@section('title')
Tài khoản
@endsection

@section('css')

<!-- App css -->
<link href="{{ asset('admins/css/icons.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('admins/css/app.min.css') }}" rel="stylesheet" type="text/css" id="light-style">
<link href="{{ asset('admins/css/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style">

<!-- third party css -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css">
@endsection

@section('content')
<div class="content-fluid">
    @php
    $route = preg_replace('/(admin)|\d/i', '', str_replace('/', '', Request::getPathInfo()));
    @endphp
    {{ Breadcrumbs::render($route, $company->id) }}
    @if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        {{ session()->get('success') }}
    </div>
    @endif
    @if ($errors->any())
    @foreach ($errors->all() as $error)
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        {{ $error }}
    </div>
    @endforeach
    @endif
    <div class="row mb-2">
        <div class="col-md-4">
            <div class="mb-2">
                <label for="taxcode" class="form-label"><i class="mdi mdi-pencil-box-outline"></i> Mã số thuế</label>
                <input type="text" class="form-control" id="taxcode" name="taxcode" placeholder="Điền mã số thuế..." value="{{ $company->taxcode }}" readonly>
            </div>
        </div>
        <div class="col-md-8">
            <div class="mb-2">
                <label for="name" class="form-label"><i class="mdi mdi-pencil-box-outline"></i> Tên Công ty</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Điền tên..." value="{{ $company->name }}" readonly>
            </div>
        </div>
        <div class="col-md-2">
            <label for="type" class="form-label"><i class="mdi mdi-pencil-box-outline"></i> Loại HĐ</label>
            <select class="form-select" id="type">
                <option value="sold" {{ app('request')->input('type') == 'sold' ? 'selected' : '' }}>Bán ra</option>
                <option value="purchase" {{ app('request')->input('type') == 'purchase' ? 'selected' : '' }}>Mua vào</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="period" class="form-label"><i class="mdi mdi-pencil-box-outline"></i> Chọn kỳ</label>
            <select class="form-select" id="period">
                <option value="today" {{ app('request')->input('period') == 'today' ? 'selected' : '' }}>Hôm nay</option>
                <option value="week" {{ app('request')->input('period') == 'week' ? 'selected' : '' }}>Tuần này</option>
                <option value="month" {{ app('request')->input('period') == 'month' ? 'selected' : '' }}>Tháng này</option>
                <option value="quarter" disabled {{ app('request')->input('period') == 'quarter' ? 'selected' : '' }}>Quý này</option>
                <option value="year" disabled {{ app('request')->input('period') == 'year' ? 'selected' : '' }}>Năm nay</option>
            </select>
        </div>
        <div class="col-md-3">
            <div class="mb-2">
                <label for="start" class="form-label">Từ ngày</label>
                <input class="form-control" id="start" type="date" name="start" value="{{ app('request')->input('start') ? app('request')->input('start') : date('Y-m-d') }}">
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-2">
                <label for="end" class="form-label">Đến ngày</label>
                <input class="form-control" id="end" type="date" name="end" value="{{ app('request')->input('end') ? app('request')->input('end') : date('Y-m-d') }}">
            </div>
        </div>
        <div class="col-md-2 text-sm-end">
            <br>
            <button type="button" class="btn btn-primary mt-1" onclick="filter();"><i class="mdi mdi-clipboard-search-outline"></i> Lọc dữ liệu</button>
        </div>
    </div>
    <hr>
    <div class="row mb-1">
        <div class="col-md-4">
            <p class="text-muted">Hiển thị {{ count($invoices) }} nội dung hóa đơn</p>
        </div>
        <div class="col-md-8">
            <div class="text-sm-end">
                <!--  -->
            </div>
        </div>
    </div>

    <div>
        <table class="table table-hover nowrap w-100" id="invoices-datatable">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Mẫu</th>
                    <th>KH</th>
                    <th>Số HĐ</th>
                    <th>TChất HĐ</th>
                    <th>Ngày tạo</th>
                    <th>Ngày TNhận</th>
                    <th>MST BBán</th>
                    <th>Tên BBán</th>
                    <th>ĐC BBán</th>
                    <th>MST BMua</th>
                    <th>Tên BMua</th>
                    <th>ĐC BMua</th>
                    <th>Tính chất</th>
                    <th>Mặt hàng</th>
                    <th>HTTT</th>
                    <th>ĐVT</th>
                    <th>SL</th>
                    <th>ĐGiá</th>
                    <th>Thành tiền</th>
                    <th>Tiền CK</th>
                    <th>ThTiền sau CK</th>
                    <th>ThSuất</th>
                    <th>Tiền VAT</th>
                    <th>Thành tiền gồm VAT</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoices as $key => $invoice)
                @php
                    $ttsckhau = floatval($invoice['thtien']);
                    $ttcckhau = floatval($invoice['stckhau']) + $ttsckhau;
                    $tthue = number_format(floatval($invoice['tsuat']) * $ttsckhau, 2, '.', '');
                    $ttien = $ttsckhau + floatval($tthue);
                @endphp
                <tr>
                    <td><b>{{ $key + 1 }}</b></td>
                    <td>{{ $invoice['khmshdon'] }}</td>
                    <td>{{ $invoice['khhdon'] }}</td>
                    <td><b class="text-{{ $invoice['ten'] == '' ? 'danger' : 'primary' }}">{{ $invoice['shdon'] }}</b></td>
                    <td>{{ $invoice['tchathd'] }}</td>
                    <td>{{ $invoice['ntao'] }}</td>
                    <td>{{ $invoice['ntnhan'] }}</td>
                    <td>{{ $invoice['nbmst'] }}</td>
                    <td><small>{{ $invoice['nbten'] }}</small></td>
                    <td><small>{{ $invoice['nbdchi'] }}</small></td>
                    <td>{{ $invoice['nmmst'] }}</td>
                    <td><small>{{ $invoice['nmten'] }}</small></td>
                    <td><small>{{ $invoice['nmdchi'] }}</small></td>
                    <td>{{ $invoice['tchat'] }}</td>
                    <td>{{ $invoice['ten'] }}</td>
                    <td>{{ $invoice['htttoan'] }}</td>
                    <td>{{ $invoice['dvtinh'] }}</td>
                    <td>{{ $invoice['sluong'] }}</td>
                    <td>{{ $invoice['dgia'] }}</td>
                    <td>{{ $ttcckhau }}</td>
                    <td>{{ $invoice['stckhau'] }}</td>
                    <td>{{ $ttsckhau }}</td>
                    <td>{{ $invoice['tsuat'] }}</td>
                    <td>{{ $tthue }}</td>
                    <td>{{ $ttien }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('script')
<script src="{{ asset('admins/js/vendor.min.js') }}"></script>
<script src="{{ asset('admins/js/app.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>
<script>
    $(document).ready(function() {
        "use strict";
        var a = $("#invoices-datatable").DataTable({
            dom: 'Bfrtip',
            scrollX: !0,
            stateSave: !0,
            lengthMenu: [
                [50, 100, 200, -1],
                ['50 rows', '100 rows', '200 rows', 'Show all'],
            ],
            buttons: [{
                    extend: 'excel',
                    text: 'Export Excel',
                    title: `${$('#type').val() == 'sold' ? 'Bán ra' : 'Mua vào'}-{{ $company->name }} ${$('#start').val()} ${$('#end').val()}`
                },
                'pageLength'
            ],
            language: {
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'>",
                    next: "<i class='mdi mdi-chevron-right'>"
                }
            },
            iDisplayLength: 50,
        });

        $('#period').on('change', function() {
            var value = $(this).val();
            var today = new Date();
            var getDay = today.getDay();
            var getDate = today.getDate();
            var getMonth = today.getMonth() + 1;
            var getYear = today.getFullYear();
            console.log(value, getDate, getMonth, getYear);
            switch (value) {
                case 'today':
                    $('#start').val(getYear + '-' + getMonth + '-' + getDate);
                    $('#end').val(getYear + '-' + getMonth + '-' + getDate);
                    break;
                case 'week':
                    var firstDate = getDate - getDay;
                    var lastDate = firstDate + 7;
                    $('#start').val(getYear + '-' + getMonth + '-' + firstDate);
                    $('#end').val(getYear + '-' + getMonth + '-' + lastDate);
                    break;
                case 'month':
                    var firstDay = new Date(getYear, today.getMonth(), 1);
                    var lastDay = new Date(getYear, today.getMonth() + 1, 0);
                    $('#start').val(firstDay.getFullYear() + '-' + parseInt(firstDay.getMonth() + 1) + '-' + (firstDay.getDate().toString().length == 1 ? '0' + firstDay.getDate() : firstDay.getDate()));
                    $('#end').val(lastDay.getFullYear() + '-' + parseInt(lastDay.getMonth() + 1) + '-' + lastDay.getDate());
                    break;
                default:
                    break;
            }
        })
    });

    function filter() {
        var type = $('#type').val();
        var period = $('#period').val();
        var start = $('#start').val();
        var end = $('#end').val();
        var params = {
            type: type,
            period: period,
            start: start,
            end: end
        };
        !type && delete params.type;
        !period && delete params.period;
        !start && delete params.start;
        !end && delete params.end;
        var searchURL = new URLSearchParams(params);
        var url = new URL(window.location.href);
        url.search = searchURL;
        window.location.href = url;
    }
</script>
@endsection
