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
                        <form class="needs-validation" novalidate="" action="{{ route('admin.account.store') }}"
                            method="post">
                            @csrf
                            <div class="row mb-2">
                                <div class="col-sm-8">
                                    <div class="text-sm-start">
                                        <a href="{{ route('admin.account') }}" class="btn btn-primary mb-2 me-1"><i
                                                class="mdi mdi-backburger"></i> DS Tài khoản</a>
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
                                            <label for="name" class="form-label">Họ và tên</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                placeholder="Điền họ tên..." value="" required>
                                        </div>
                                        <div class="invalid-feedback">
                                            Mời nhập tên!
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Địa chỉ</label>
                                            <input type="text" class="form-control" id="address" name="address"
                                                placeholder="Điền địa chỉ..." value="">
                                        </div>
                                        <div class="invalid-feedback">
                                            Mời nhập địa chỉ!
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Tên đăng nhập</label>
                                            <input type="text" class="form-control" id="username" name="username"
                                                placeholder="Điền tên đăng nhập..." value="" required>
                                        </div>
                                        <div class="invalid-feedback">
                                            Mời nhập tên đăng nhập!
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                placeholder="Điền Email..." value="" required>
                                        </div>
                                        <div class="invalid-feedback">
                                            Mời nhập địa chỉ email!
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Mật khẩu (mặc định: 123456aA@)</label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                placeholder="Điền mật khẩu..." value="123456aA@" required>
                                        </div>
                                        <div class="invalid-feedback">
                                            Mời nhập mật khẩu!
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="image" class="form-label">Ảnh đại diện</label>
                                            <input type="file" class="form-control" name="image" id="image"
                                                value="">
                                        </div>
                                    </div> <!-- end col -->
                                    <!-- end col -->
                                </div> <!-- end row -->

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="mobile" class="form-label">Điện thoại</label>
                                            <input type="text" class="form-control" id="mobile" name="mobile"
                                                placeholder="Điền SĐT..." value="">
                                        </div>
                                    </div> <!-- end col -->
                                    <div class="col-md-1">
                                        <label for="">Giới tính</label>
                                        <div class="form-check form-checkbox-success mb-3">
                                            <br>
                                            <input type="checkbox" class="form-check-input" id="male"
                                                name="male" value="1" checked>
                                            <label class="form-check-label" for="male">Nam</label>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-check form-checkbox-success mb-3">
                                            <br><br>
                                            <input type="checkbox" class="form-check-input" id="female"
                                                name="female" value="0">
                                            <label class="form-check-label" for="female">Nữ</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4"></div>
                                </div> <!-- end row -->

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="birthday" class="form-label">Ngày sinh</label>
                                            <input type="date" class="form-control" id="birthday" name="birthday"
                                                placeholder="Enter birthday" value="">
                                        </div>
                                    </div>
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
