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
    <div class="container-fluid">
        <!-- start page title -->
        @php
            $route = preg_replace('/(admin)|\d/i', '', str_replace('/', '', Request::getPathInfo()));
        @endphp
        {{ Breadcrumbs::render($route) }}
        <!-- end page title -->

        <div class="row mb-2">
            @can('inv.add')
                <div class="col-sm-4">
                    <a href="{{ route('admin.invoice.add') }}" class="btn btn-primary btn-rounded mb-3"><i
                            class="mdi mdi-plus"></i> Create invoice</a>
                </div>
            @endcan

            <div class="col-sm-8">
                <div class="text-sm-end">
                </div>
            </div><!-- end col-->
        </div>

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
            @forelse ($invoices as $key => $invoice)
                <div class="col-md-12 mb-2">
                    <!-- Portlet card -->
                    <div class="card border-primary border text-primary mb-md-0 mb-3">
                        <div class="card-body">
                            <div class="card-widgets">
                                <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                                <a data-bs-toggle="collapse" href="#card{{ $invoice->id }}" role="button"
                                    aria-expanded="true" aria-controls="card{{ $invoice->id }}" class="collapsed"><i
                                        class="mdi mdi-minus"></i></a>
                                @can('inv.edit')
                                    <a href="{{ route('admin.invoice.edit', $invoice->id) }}"><i
                                            class="mdi mdi-square-edit-outline"></i></a>
                                @endcan
                                @can('inv.delete')
                                    <a href="{{ route('admin.invoice.delete', $invoice->id) }}"><i
                                            class="mdi mdi-delete"></i></a>
                                @endcan
                                {{-- <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a> --}}
                            </div>
                            <h5 class="card-title mb-0">
                                ID:{{ $invoice->id }} | <span
                                    class="text-info">{{ $invoice->invoice_title }}&nbsp;</span>&nbsp;<span class="text-success"><b>[Loại: {{ $invoice->invoice_type }} - Ưu tiên: {{ $invoice->invoice_status }}]</b></span><i><small
                                        class="text-muted">{{ $invoice->created_at }}</small></i>
                            </h5>
                            {{-- show --}}
                            <div id="card{{ $invoice->id }}" class="collapse pt-3 ">
                                <textarea class="tinymce" name="invoice_detail">{{ $invoice->invoice_detail }}</textarea>
                            </div>
                        </div>
                    </div> <!-- end card-->
                </div><!-- end col -->
            @empty
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    No data
                </div>
            @endforelse
        </div>
    </div>
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
    </script>
@endsection
