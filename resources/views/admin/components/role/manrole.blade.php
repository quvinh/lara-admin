@extends('admin.home.master')

@section('title')
Admin | Quyền hạn
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
                            <div class="col-sm-4">
                                <div class="text-sm-start">
                                    {{-- <button type="button" class="btn btn-success mb-2 me-1"><i
                                            class="mdi mdi-cog"></i></button>
                                    <button type="button" class="btn btn-light mb-2 me-1">Import</button>
                                    <button type="button" class="btn btn-light mb-2">Export</button> --}}
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <form action="{{ route('admin.role.add') }}" method="post" class="text-sm-end">
                                    @csrf
                                    <div class="row">
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Nhập quyền mới..." value="" required />
                                    </div>
                                    <div class="col-sm-3">
                                        <button class="btn btn-primary" type="submit" style="width:100%;"><i
                                                class="mdi mdi-plus-circle me-2"></i> Add Role</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                            <!-- end col-->
                        </div>

                        <div class="table-responsive">
                            <table class="table table-centered table-striped dt-responsive nowrap w-100"
                                id="roles-datatable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Role</th>
                                        <th>Amount</th>
                                        <th style="width: 75px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $key => $role)
                                        <tr>
                                            <td>
                                                {{ $role->id }}
                                            </td>
                                            <td class="table-user">
                                                {{-- <img src="{{ asset('admins/images/users/avatar-4.jpg') }}" alt="table-user"
                                                    class="me-2 rounded-circle"> --}}
                                                <a href="{{ route('admin.role.edit', $role->id) }}"
                                                    class="text-body fw-semibold">{{ $role->name }}</a>
                                            </td>
                                            <td>
                                                {{ count($role->permissions) }}
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.role.edit', $role->id) }}" class="action-icon"> <i
                                                        class="mdi mdi-square-edit-outline"></i></a>
                                                <a href="{{ route('admin.role.delete', $role->id) }}" class="action-icon"> <i
                                                        class="mdi mdi-delete"></i></a>
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
            const roles = <?php echo json_encode($roles->pluck('id')); ?>;
            console.log(roles);
            $("#roles-datatable").DataTable({
                language: {
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>",
                        next: "<i class='mdi mdi-chevron-right'>"
                    },
                    info: "Showing roles _START_ to _END_ of _TOTAL_",
                    lengthMenu: 'Display <select class=\'form-select form-select-sm ms-1 me-1\'><option value="20">20</option><option value="50">50</option><option value="100">100</option><option value="-1">All</option></select> '
                },
                pageLength: 20,
                columns: [
                //     {
                //     orderable: !1,
                //     render: function(e, l, a, o) {
                //         return "display" === l && (e =
                //             '<div class="form-check"><input type="checkbox" id="check' +
                //             roles[o.row] +
                //             '" name="checklist[]" class="checkonly form-check-input dt-checkboxes"><label class="form-check-label">&nbsp;</label></div>'
                //         ), e
                //     },
                //     checkboxes: {
                //         selectRow: !0,
                //         selectAllRender: '<div class="form-check"><input type="checkbox" id="checkall" class="form-check-input dt-checkboxes"><label class="form-check-label">&nbsp;</label></div>'
                //     }
                // },
                {
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
                order: [
                    [3, "asc"]
                ],
                drawCallback: function() {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded"), $(
                        "#roles-datatable_length label").addClass("form-label")
                },
            })

            // $('#btn-delete').click(function() {
            //     console.log('button delete clicked');
            //     const checkboxes = document.getElementsByName('checklist[]');
            //     const selectlist = [];
            //     for (var item of checkboxes) {
            //         item.checked && selectlist.push(item.id);
            //     }
            //     if (selectlist.length > 0) {
            //         notify("Đang xóa", "Chờ trong giây lát", "info");
            //     } else {
            //         notify("Opps", "Bạn chưa chọn gì cả!", "info");
            //     }
            // })
        });

        // function notify(title, content, alert) {
        //     $.NotificationApp.send(title, content, "top-right", "rgba(0,0,0,0.2)", alert);
        // }
    </script>
@endsection
