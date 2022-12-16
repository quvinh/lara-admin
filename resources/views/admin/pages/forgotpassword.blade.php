<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Forgot Password | LAB</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('admin/images/layer.png') }}">

    <!-- App css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="light-style" />
    <link href="{{ asset('assets/css/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style" />

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
                                <span><img src="{{ asset('admin/images/logo.png') }}" alt="LOGO" height="25"></span>
                            </a>
                        </div>

                        <div class="card-body p-4">
                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                        {{ $error }}
                                    </div>
                                @endforeach
                            @endif
                            @if (session()->has('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                    {{ session()->get('success') }}
                                </div>
                            @endif

                            <div class="text-center w-75 m-auto">
                                <h4 class="text-dark-50 text-center mt-0 fw-bold">@lang('resetpassword.forgot')</h4>
                                <p class="text-muted mb-4">@lang('resetpassword.forgotcontent')</p>
                            </div>

                            <form action="{{ route('forgot-password') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="emailaddress" class="form-label">@lang('resetpassword.email')</label>
                                    <input class="form-control" type="email" id="emailaddress" name="email"
                                        required="" placeholder="@lang('resetpassword.enteremail')">
                                </div>

                                <div class="mb-0 text-center">
                                    <button class="btn btn-primary" type="submit">@lang('resetpassword.submit')</button>
                                </div>
                            </form>
                        </div> <!-- end card-body-->
                    </div>
                    <!-- end card -->

                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <p class="text-muted">@lang('resetpassword.backto') <a href="{{ route('login') }}" class="text-muted ms-1"><b>@lang('resetpassword.login')</b></a></p>
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
    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>

</body>

</html>
