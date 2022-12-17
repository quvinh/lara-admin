@extends('admin.home.master')

@section('title')
Công ty
@endsection

@section('css')
<!-- third party css -->
<link href="{{ asset('admins/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('admins/css/vendor/responsive.bootstrap5.css') }}" rel="stylesheet" type="text/css">
<!-- third party css end -->

<!-- App css -->
<link href="{{ asset('admins/css/icons.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('admins/css/app.min.css') }}" rel="stylesheet" type="text/css" id="light-style">
<link href="{{ asset('admins/css/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style">
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    @php
    $route = preg_replace('/(admin)|\d/i', '', str_replace('/', '', Request::getPathInfo()));
    @endphp
    {{ Breadcrumbs::render($route) }}
    <!-- end page title -->
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

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="text-sm-end">
                            @can('com.add')
                            <a href="{{ route('admin.company.create') }}" class="btn btn-primary btn-sm"><i class="mdi mdi-plus-circle me-2"></i> Add Company</a>
                            @endcan
                        </div>
                        <!-- end col-->
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-striped dt-responsive nowrap w-100" id="companies-datatable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên công ty</th>
                                    <th>MST</th>
                                    <th>Code</th>
                                    <th>Ngày tạo</th>
                                    <th>Trạng thái</th>
                                    <th style="width: 75px;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($companies as $key => $company)
                                <tr>
                                    <td>
                                        {{ $company->id }}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.company.edit', $company->id) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">{{ $company->name }}</a>
                                    </td>
                                    <td>
                                        <b>{{ $company->taxcode }}</b>
                                    </td>
                                    <td>
                                        {{ $company->code }}
                                    </td>
                                    <td>
                                        {{ date('d/m/Y', strtotime($company->created_at)) }}
                                    </td>
                                    <td>
                                        @if($company->token == '')
                                        <i class="mdi mdi-square text-danger"></i> Chưa có token
                                        @else
                                        <i class="mdi mdi-square text-success"></i> Có token
                                        @endif
                                    </td>
                                    <td>
                                        @can('com.edit')
                                        <a href="{{ route('admin.company.invoice', $company->id) }}" class="btn btn-sm btn-secondary me-1"><i class="mdi mdi-file-search-outline"></i></a>
                                        <a href="{{ route('admin.company.edit', $company->id) }}" class="btn btn-sm btn-warning me-1"><i class="mdi mdi-square-edit-outline"></i></a>
                                        @endcan
                                        @can('com.delete')
                                        <a href="{{ route('admin.company.destroy', $company->id) }}" class="btn btn-sm btn-danger"><i class="mdi mdi-delete"></i></a>
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
    <!-- end row -->

</div> <!-- container -->
@endsection

@section('script')
<!-- bundle -->
<script src="{{ asset('admins/js/vendor.min.js') }}"></script>
<script src="{{ asset('admins/js/app.min.js') }}"></script>

<!-- third party js -->
<script src="{{ asset('admins/js/vendor/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admins/js/vendor/dataTables.bootstrap5.js') }}"></script>
<script src="{{ asset('admins/js/vendor/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('admins/js/vendor/responsive.bootstrap5.min.js') }}"></script>
<script src="{{ asset('admins/js/vendor/dataTables.checkboxes.min.js') }}"></script>
<!-- demo js -->
<script src="{{ asset('admins/js/pages/demo.toastr.js') }}"></script>
<!-- -->
<!-- third party js ends -->

<script>
    $(document).ready(function() {
        "use strict";
        $("#companies-datatable").DataTable({
            language: {
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'>",
                    next: "<i class='mdi mdi-chevron-right'>"
                },
                info: "Showing accounts _START_ to _END_ of _TOTAL_",
                lengthMenu: 'Display <select class="form-select form-select-sm ms-1 me-1"><option value="50">50</option><option value="100">100</option><option value="200">200</option><option value="-1">All</option></select>'
            },
            pageLength: 50,
            columns: [{
                orderable: !0
            }, {
                orderable: !0
            }, {
                orderable: !0
            }, {
                orderable: !0
            }, {
                orderable: !0
            }, {
                orderable: !0
            }, {
                orderable: !1
            }],
            select: {
                style: "multi"
            },
            // order: [
            //     [1, "asc"]
            // ],
            drawCallback: function() {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded"), $(
                    "#companies-datatable_length label").addClass("form-label")
            },
        })
    });

    function notify(title, content, alert) {
        $.NotificationApp.send(title, content, "top-right", "rgba(0,0,0,0.2)", alert);
    }
</script>
@endsection
