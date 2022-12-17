@extends('admin.home.master')

@section('title')
Thông tin tài khoản
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
        {{ Breadcrumbs::render($route) }}
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
            <div class="col-xl-4 col-lg-6">
                <div class="card text-center">
                    <div class="card-body">
                        <img src="{{ $profile->image == null ? asset('admins/images/users/avatar.png') : asset($profile->image) }}"
                            class="rounded-circle avatar-xl img-thumbnail" alt="profile-image">

                        <h4 class="mb-0 mt-2">{{ $profile->name }}</h4>

                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div>
            <div class="col-xl-8 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                            <li class="nav-item">
                                <a href="#aboutme" data-bs-toggle="tab" aria-expanded="true"
                                    class="nav-link rounded-0 active">
                                    @lang('components/profile.myaccount')
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="#settings" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0">
                                    @lang('components/profile.changepassword')
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane show active" id="aboutme">
                                <form class="" action="{{ route('admin.profile.update', $profile->id) }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    {{-- @method('PUT') --}}
                                    <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle me-1"></i> @lang('components/profile.accountinfo')</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">@lang('components/profile.fullname')</label>
                                                <input type="text" class="form-control" id="name" name="name"
                                                    placeholder="@lang('components/profile.entername')" required
                                                    value="{{ old('name') ? old('name') : $profile->name }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="address" class="form-label">@lang('components/profile.address')</label>
                                                <input type="text" class="form-control" id="address" name="address"
                                                    placeholder="@lang('components/profile.enteraddress')" required
                                                    value="{{ old('address') ? old('address') : $profile->address }}">
                                            </div>
                                        </div> <!-- end col -->
                                    </div> <!-- end row -->

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="username" class="form-label">@lang('components/profile.username')</label>
                                                <input type="text" class="form-control" id="username"
                                                    name="username" placeholder="@lang('components/profile.enterusername')" required readonly
                                                    value="{{ old('username') ? old('username') : $profile->username }}">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label for="email" class="form-label">@lang('components/profile.email')</label>
                                                <input type="email" class="form-control" id="email" name="email"
                                                    placeholder="@lang('components/profile.enteremail')" required
                                                    value="{{ old('email') ? old('email') : $profile->email }}">
                                            </div>
                                        </div> <!-- end col -->
                                    </div> <!-- end row -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="mobile" class="form-label">@lang('components/profile.mobile')</label>
                                                <input type="text" class="form-control" id="mobile"
                                                    name="mobile" placeholder="@lang('components/profile.entermobile')" required
                                                    value="{{ old('mobile') ? old('mobile') : $profile->mobile }}">
                                            </div>
                                        </div> <!-- end col -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="image" class="form-label">@lang('components/profile.avatar')</label>
                                                <input type="file" class="form-control" name="image" id="image"
                                                    value="{{ old('avatar') ? old('avatar') : $profile->avatar }}">
                                            </div>
                                        </div> <!-- end col -->
                                    </div> <!-- end row -->

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="birthday" class="form-label">@lang('components/profile.birthday')</label>
                                                <input type="date" class="form-control" id="birthday"
                                                    name="birthday" placeholder="@lang('components/profile.enterbirthday')"
                                                    value="{{ old('birthday') ? old('birthday') : $profile->birthday }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">@lang('components/profile.gender')</label>
                                            <div class="mt-2">
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" id="male" name="gender" value="1" class="form-check-input" {{ $profile->gender == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="male">@lang('components/profile.male')</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" id="female" name="gender" value="0" class="form-check-input" {{ $profile->gender == '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="female">@lang('components/profile.female')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- end row -->
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-success mt-2"><i
                                                class="mdi mdi-content-save"></i> @lang('components/profile.save')</button>
                                    </div>
                                </form>

                            </div> <!-- end tab-pane -->
                            <!-- end about me section content -->

                            <div class="tab-pane" id="settings">
                                <form class="" action="{{ route('admin.profile.update-pass', $profile->id) }}"
                                    method="POST">
                                    @csrf
                                    @method('put')
                                    {{-- @method('PUT') --}}
                                    <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle me-1"></i> @lang('components/profile.passwordinfo')</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="current-password" class="form-label">@lang('components/profile.currentpass')</label>
                                                <div class="input-group input-group-merge">
                                                    <input type="password" id="password" class="form-control" name="password"
                                                        placeholder="@lang('components/profile.enterpass')" value="{{ old('password') ? old('password') : '' }}">
                                                    <div class="input-group-text" data-password="false">
                                                        <span class="password-eye"></span>
                                                    </div>
                                                </div>
                                                <!-- <input type="password" class="form-control" id="current-password"
                                                    name="current_password" placeholder="@lang('components/profile.enterpass')"
                                                    value="{{ old('password') ? old('password') : '' }}" required> -->
                                            </div>
                                        </div> <!-- end col -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="password" class="form-label">@lang('components/profile.newpass')</label>
                                                <input type="password" class="form-control" id="password"
                                                    name="new_password" placeholder="@lang('components/profile.enterpass')"
                                                    value="{{ old('password') ? old('password') : '' }}" required>
                                            </div>
                                        </div> <!-- end col -->
                                    </div> <!-- end row -->

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-success mt-2"><i
                                                class="mdi mdi-content-save"></i> @lang('components/profile.save')</button>
                                    </div>
                                </form>
                            </div>
                            <!-- end settings content-->

                        </div> <!-- end tab-content -->
                    </div> <!-- end card body -->
                </div> <!-- end card -->
            </div> <!-- end col -->
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('admins/js/vendor.min.js') }}"></script>
    <script src="{{ asset('admins/js/app.min.js') }}"></script>
@endsection
