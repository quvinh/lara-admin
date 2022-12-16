<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Log In | LAB</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('admins/images/layer.png') }}">

    <!-- App css -->
    <link href="{{ asset('admins/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admins/css/app.min.css') }}" rel="stylesheet" type="text/css" id="light-style" />
    <link href="{{ asset('admins/css/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style" />

</head>

<body class="loading authentication-bg"
    data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>
    @include('admin.home.preloader')
    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-lg-5">
                    <div class="card">

                        <!-- Logo -->
                        <div class="card-header pt-2 pb-2 text-center bg-primary">
                            <a href="">
                                <span><img src="{{ asset('admins/images/logo.png') }}" alt="LOGO" height="25"></span>
                            </a>
                        </div>

                        <div class="card-body p-4">
                            @if(session()->has('error'))
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                                {{ session()->get('error') }}
                            </div>
                            @endif
                            @if(session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                                {{ session()->get('success') }}
                            </div>
                            @endif
                            <div class="text-center w-75 m-auto">
                                <h4 class="text-dark-50 text-center pb-0 fw-bold">@lang('login.signin')</h4>
                                {{-- <p class="text-muted mb-4">Enter your username and password to access admin panel. --}}
                                </p>
                            </div>

                            <form action="{{ route('admin.login') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="username" class="form-label">@lang('login.username')</label>
                                    <input class="form-control" type="text" id="username" name="username" required=""
                                        placeholder="@lang('login.enterusername')" value="{{ old('username') }}">
                                </div>

                                <div class="mb-3">
                                    {{-- <a href="pages-recoverpw.html" class="text-muted float-end"><small>Forgot your
                                            password?</small></a> --}}
                                    <label for="password" class="form-label">@lang('login.password')</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password" class="form-control" name="password"
                                            placeholder="@lang('login.enterpassword')" value="{{ old('password') }}">
                                        <div class="input-group-text" data-password="false">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="checkbox-signin"
                                            name="remember_me" checked>
                                        <label class="form-check-label" for="checkbox-signin">@lang('login.rememberme')</label>
                                    </div>
                                </div>

                                <div class="mb-3 mb-0 text-center">
                                    <button class="btn btn-primary" type="submit"> @lang('login.login') </button>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-6 text-center">
                                        <a href="/locale/gb"><img src="{{ asset('assets/flags/4x3/gb.svg') }}" alt="user-image" class="me-1" height="12"> <span
                                            class="align-middle">@lang('login.english')</span></a>
                                    </div>
                                    <div class="col-sm-6 text-center">
                                        <a href="/locale/vn"><img src="{{ asset('assets/flags/4x3/vn.svg') }}" alt="user-image" class="me-1" height="12"> <span
                                            class="align-middle">@lang('login.vietnam')</span></a>
                                    </div>
                                </div>

                            </form>
                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->

                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <p class="text-muted"><a href="#"
                                    class="text-muted ms-1"><b>@lang('login.forgot')?</b></a></p>
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    <footer class="footer footer-alt">
        2022 © Dailythue - thuemienbac.vn
    </footer>

    <!-- bundle -->
    <script src="{{ asset('admins/js/vendor.min.js') }}"></script>
    <script src="{{ asset('admins/js/app.min.js') }}"></script>

</body>

</html>
