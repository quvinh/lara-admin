@extends('admin.home.master')

@section('title')
Admin | Mẫu phiếu
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
                        <div class="tab-content">
                            <div class="tab-pane show active" id="custom-styles-preview">
                                <form class="needs-validation" novalidate="" method="POST"
                                    action="{{ route('admin.invoice.store') }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="invoice_title">Tiêu đề</label>
                                                <input type="text" class="form-control" id="invoice_title"
                                                    name="invoice_title" placeholder="Title"
                                                    value="{{ old('invoice_title') }}" required="">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label" for="invoice_status">Độ ưu tiên</label>
                                            <input type="number" value="100" class="form-control" id="invoice_status" min="0" max="100" step="5"
                                                name="invoice_status" placeholder="Priority"
                                                value="{{ old('invoice_status') }}" required="">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="invoice_type">Loại Phiếu</label>
                                            <select data-toggle="select2" title="Type" id="invoice_type" name="invoice_type">
                                                <option value="1">1. Phiếu thu</option>
                                                <option value="2">2. Phiếu kết quả (full)</option>
                                                <option value="3" selected>3. Phiếu kết quả</option>
                                                <option value="4">4. Phiếu 4...</option>
                                                <option value="5">5. Phiếu 5...</option>
                                                <option value="6">6. Phiếu 6...</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <textarea class="tinymce" name="invoice_detail" id="invoice_detail"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <div class="form-check form-check-inline">
                                            <input type="radio" id="customRadio3" name="station_action"
                                                class="form-check-input" value="add" checked>
                                            <label class="form-check-label" for="customRadio3">Continue Add</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" id="customRadio4" name="station_action"
                                                class="form-check-input" value="list">
                                            <label class="form-check-label" for="customRadio4">Redirect to list</label>
                                        </div>
                                    </div>
                                    <br>
                                    <button class="btn btn-primary" type="submit">Submit form</button>
                                </form>
                            </div> <!-- end preview-->

                        </div> <!-- end tab-content-->

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
    <script src="{{ asset('tinymce/tinymce.min.js') }}"></script>
    <script>
        tinymce.init({
            model: 'dom',
            selector: '.tinymce',
            plugins: 'code preview importcss searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists  wordcount help charmap quickbars emoticons',
            toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | forecolor backcolor casechange permanentpen formatpainter removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media pageembed template link anchor codesample | a11ycheck ltr rtl | showcomments addcomment',
            height: "480",
        });
        $(document).ready(function() {
            "use strict";
            $("input:checkbox").on('click', function() {
                var $box = $(this);
                if ($box.is(":checked")) {
                    var group = "input:checkbox[name='" + $box.attr("name") + "']";
                    $(group).prop("checked", false);
                    $box.prop("checked", true);
                } else {
                    $box.prop("checked", false);
                }
            });
        })
    </script>
@endsection
