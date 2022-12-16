<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <title>Admin | @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Admin" name="description">
    <meta content="Quang Vinh" name="author">
    <meta http-equiv="refresh" content="1800">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('images/register/logodhy.png') }}">

    @yield('css')

</head>

<body class="loading"
    data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>
    @include('admin.home.preloader')
    <!-- Begin page -->
    <div class="wrapper">
        @include('admin.home.leftside')
        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">
                @include('admin.home.header')
                @yield('content')
            </div> <!-- content -->

            @include('admin.home.footer')

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->
    </div>
    <!-- END wrapper -->
    @include('admin.home.rightside')
    @yield('script')

</body>

</html>