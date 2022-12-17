@extends('admin.home.master')

@section('title')
Tài khoản
@endsection

@section('css')
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
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form class="needs-validation" novalidate="" action="{{ route('admin.company.store') }}"
                            method="post">
                            @csrf
                            <div class="row mb-2">
                                <div class="col-sm-8">
                                    <div class="text-sm-start">
                                        <a href="{{ route('admin.company') }}" class="btn btn-primary mb-2 me-1"><i
                                                class="mdi mdi-backburger"></i> DS Công ty</a>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="text-sm-end">
                                        <button type="submit" class="btn btn-success"><i
                                                class="mdi mdi-content-save"></i>
                                            Lưu</button>
                                    </div>
                                </div>
                                <!-- end col-->
                            </div>
                            <div class="tab-content">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label"><i class="mdi mdi-pencil-box-outline"></i> Tên Công ty</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                placeholder="Điền tên..." value="" required>
                                        </div>
                                        <div class="invalid-feedback">
                                            Mời nhập tên!
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label for="manager" class="form-label"><i class="mdi mdi-pencil-box-outline"></i> Người đại diện</label>
                                            <input type="text" class="form-control" id="manager" name="manager"
                                                placeholder="Điền Người đại diện..." value="">
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="taxcode" class="form-label"><i class="mdi mdi-pencil-box-outline"></i> Mã số thuế</label>
                                            <input type="text" class="form-control" id="taxcode" name="taxcode"
                                                placeholder="Điền mã số thuế..." value="" required>
                                        </div>
                                        <div class="invalid-feedback">
                                            Mời nhập mã số thuế!
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label for="role" class="form-label"><i class="mdi mdi-pencil-box-outline"></i> Chức vụ</label>
                                            <input type="text" class="form-control" id="role" name="role"
                                                placeholder="Điền Chức vụ..." value="">
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="code" class="form-label"><i class="mdi mdi-pencil-box-outline"></i> Mã đăng nhập</label>
                                            <input type="text" class="form-control" id="code" name="code"
                                                placeholder="Điền mã đăng nhập..." value="" required>
                                        </div>
                                        <div class="invalid-feedback">
                                            Mời nhập mã đăng nhập!
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="mobile" class="form-label"><i class="mdi mdi-pencil-box-outline"></i> Điện thoại</label>
                                            <input type="text" class="form-control" id="mobile" name="mobile"
                                                placeholder="Điền SĐT..." value="">
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="token" class="form-label"><i class="mdi mdi-pencil-box-outline"></i> Token</label>
                                            <input type="text" class="form-control" id="token" name="token"
                                                placeholder="Điền Token..." value="">
                                        </div>
                                    </div> <!-- end col -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="address" class="form-label"><i class="mdi mdi-pencil-box-outline"></i> Địa chỉ</label>
                                            <input type="text" class="form-control" id="address" name="address"
                                                placeholder="Điền Địa chỉ..." value="">
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->

                                <div class="text-end">

                                </div>
                            </div> <!-- end tab-content-->
                        </form>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div>
        <!-- end row -->

    </div> <!-- container -->
@endsection

@section('script')
    <!-- bundle -->
    <script src="{{ asset('admins/js/vendor.min.js') }}"></script>
    <script src="{{ asset('admins/js/app.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            $('input[type="checkbox"]').click(function() {
                $('input[type="checkbox"]').not(this).prop("checked", false);
            });
        });
    </script>
@endsection
