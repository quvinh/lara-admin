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
    <div class="content-fluid">
        @php
            $route = preg_replace('/(admin)|\d/i', '', str_replace('/', '', Request::getPathInfo()));
        @endphp
        {{ Breadcrumbs::render($route, $account->id) }}
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
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.account.edit', $account->id)}}" method="POST" id="position" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="text-end">
                                <button type="submit" class="btn btn-success mt-2"><i class="mdi mdi-content-save"></i>
                                    Save</button>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    {{-- HERE --}}
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <label for="select2"><i class="mdi mdi-square-edit-outline"></i> Role</label>
                                    <select data-toggle="select2" name="role" id="role">
                                        <option value="">Chọn nhóm quyền</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}" {{$role->name == $account->getRoleNames()->first() ? 'selected' : ''}}>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle me-1"></i> Personal
                                Info</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label"><i class="mdi mdi-square-edit-outline"></i> Full Name</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Enter full name"
                                            value="{{ old('name') ? old('name') : $account->name }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="address" class="form-label"><i class="mdi mdi-square-edit-outline"></i> Address</label>
                                        <input type="text" class="form-control" id="address" name="address"
                                            placeholder="Enter Address"
                                            value="{{ old('address') ? old('address') : $account->address }}">
                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row -->

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="username" class="form-label"><i class="mdi mdi-square-edit-outline"></i> Username</label>
                                        <input type="text" class="form-control" id="username" name="username"
                                            placeholder="Enter username"
                                            value="{{ old('username') ? old('username') : $account->username }}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label"><i class="mdi mdi-square-edit-outline"></i> Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            placeholder="Enter Email"
                                            value="{{ old('email') ? old('email') : $account->email }}">
                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row -->

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="birthday" class="form-label"><i class="mdi mdi-square-edit-outline"></i> Birthday</label>
                                        <input type="date" class="form-control" id="birthday" name="birthday"
                                            placeholder="Enter birthday"
                                            value="{{ old('birthday') ? old('birthday') : $account->birthday }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="image" class="form-label"><i class="mdi mdi-square-edit-outline"></i> New Avatar</label>
                                        <input type="file" class="form-control" name="image" id="image"
                                            value="{{ old('image') ? old('image') : $account->image }}">
                                    </div>
                                </div> <!-- end col --> <!-- end col -->
                            </div> <!-- end row -->

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="mobile" class="form-label"><i class="mdi mdi-square-edit-outline"></i> Mobile</label>
                                        <input type="text" class="form-control" id="mobile" name="mobile"
                                            placeholder="Enter mobile"
                                            value="{{ old('mobile') ? old('mobile') : $account->mobile }}">
                                    </div>
                                </div> <!-- end col -->
                                <div class="col-md-1">
                                    <label for=""><i class="mdi mdi-square-edit-outline"></i> Gender</label>
                                    <div class="form-check form-checkbox-success mb-3">
                                        <br>
                                        <input type="checkbox" class="form-check-input" id="male" name="male"
                                            {{ $account->gender == '1' ? 'checked' : '' }} value="1">
                                        <label class="form-check-label" for="male">Male</label>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-check form-checkbox-success mb-3">
                                        <br><br>
                                        <input type="checkbox" class="form-check-input" id="female" name="female"
                                            {{ $account->gender == '0' ? 'checked' : '' }} value="0">
                                        <label class="form-check-label" for="female">Female</label>
                                    </div>
                                </div>
                                <div class="col-md-4"></div>
                            </div> <!-- end row -->
                        </form>
                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div>
        </div>
    </div>
@endsection

@section('script')
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
