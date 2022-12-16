@extends('admin.home.master')

@section('title')
Quyền hạn
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
        {{ Breadcrumbs::render($route, $role->id) }}
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
        <form action="{{ route('admin.role.update', $role->id) }}" method="post">
            @csrf
            @method('put')
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <a href="{{ route('admin.role') }}" class="btn btn-info" type="button"><i class="mdi mdi-menu-open me-2"></i> Back
                                        to list</a>
                                </div>
                                <div class="col-sm-8">
                                    <div class="row">
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="role" name="role"
                                                placeholder="Nhập quyền..." value="{{ $role->name }}" required />
                                            <input type="text" name="permission" required hidden read-only>
                                        </div>
                                        <div class="col-sm-3">
                                            <button class="btn btn-primary" type="submit" style="width:100%;"><i
                                                    class="mdi mdi-plus-circle me-2"></i> Submit</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- end col-->
                            </div>
                            <table class="table table-hover table-centered mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:40%;"><i class="h4 mdi mdi-format-list-bulleted"></i></th>
                                        <th style="width:10%;"><i class="h4 mdi mdi-marker-check text-info"></i> All</th>
                                        <th style="width:10%;"><i class="h4 mdi mdi-eye-check text-primary"></i> Xem</th>
                                        <th style="width:10%;"><i class="h4 mdi mdi-plus-circle text-success"></i> Thêm</th>
                                        <th style="width:10%;"><i class="h4 mdi mdi-square-edit-outline text-warning"></i>
                                            Sửa
                                        </th>
                                        <th style="width:10%;"><i class="h4 mdi mdi-delete-circle text-danger"></i> Xóa</th>
                                        <th style="width:10%;"><i class="h4 mdi mdi-square-edit-outline text-secondary"></i>
                                            Duyệt
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($groups as $key => $value)
                                        <tr>
                                            <td><b class="text-primary">{{ $value }}</b></td>
                                            <td>
                                                <div class="form-check form-checkbox-info mb-2">
                                                    <input type="checkbox" class="form-check-input text-center"
                                                        id="{{ $key }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-check mb-2" id="{{ $key . '_check' }}">
                                                    <input type="checkbox" class="form-check-input text-center"
                                                        id="{{ $key . '.view' }}" name="{{ $key . '[]' }}"
                                                        {{ in_array($key . '.view', $permission->pluck('name')->toArray()) ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-check form-checkbox-success mb-2"
                                                    id="{{ $key . '_check' }}">
                                                    <input type="checkbox" class="form-check-input text-center"
                                                        id="{{ $key . '.add' }}" name="{{ $key . '[]' }}"
                                                        {{ in_array($key . '.add', $permission->pluck('name')->toArray()) ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-check form-checkbox-warning mb-2"
                                                    id="{{ $key . '_check' }}">
                                                    <input type="checkbox" class="form-check-input text-center"
                                                        id="{{ $key . '.edit' }}" name="{{ $key . '[]' }}"
                                                        {{ in_array($key . '.edit', $permission->pluck('name')->toArray()) ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-check form-checkbox-danger mb-2"
                                                    id="{{ $key . '_check' }}">
                                                    <input type="checkbox" class="form-check-input text-center"
                                                        id="{{ $key . '.delete' }}" name="{{ $key . '[]' }}"
                                                        {{ in_array($key . '.delete', $permission->pluck('name')->toArray()) ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-check form-checkbox-secondary mb-2"
                                                    id="{{ $key . '_check' }}">
                                                    <input type="checkbox" class="form-check-input text-center"
                                                        id="{{ $key . '.confirm' }}" name="{{ $key . '[]' }}"
                                                        {{ in_array($key . '.confirm', $permission->pluck('name')->toArray()) ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                    @endforelse
                                </tbody>
                            </table>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col -->
            </div>
        </form>
        <!-- end row -->

    </div> <!-- container -->

@endsection

@section('script')
    <!-- bundle -->
    <script src="{{ asset('admins/js/vendor.min.js') }}"></script>
    <script src="{{ asset('admins/js/app.min.js') }}"></script>
    <script>
        const groups = <?php echo json_encode($groups); ?>;
        const array = [];
        for (var key in groups) {
            array.push(key);
        }
        $(document).ready(function() {
            function currentPermission() {
                const arrayPermission = [];
                array.forEach(item => {
                    const checkbox = document.getElementsByName(item + '[]');
                    for (var i = 0; i < checkbox.length; i++) {
                        checkbox[i].checked && arrayPermission.push(checkbox[i].getAttributeNode('id')
                            .value);
                    }
                })
                return arrayPermission;
            }

            array.forEach(element => {
                if ($('#' + element + '_check > input:checked').length == 5) {
                    $('#' + element).prop('checked', true);
                }

                $('#' + element).on('click', function() {
                    if (this.checked) {
                        $('#' + element + '_check > input').each(function() {
                            this.checked = true;
                        })
                    } else {
                        $('#' + element + '_check > input').each(function() {
                            this.checked = false;
                        })
                    }
                    currentPermission();
                    $("input[name=permission]").val(currentPermission());
                })

                $('#' + element + '_check > input').on('click', function() {
                    console.log($('#' + element + '_check > input:checked').length);
                    if ($('#' + element + '_check > input:checked').length == 5) {
                        $('#' + element).prop('checked', true);
                    } else {
                        $('#' + element).prop('checked', false);
                    }
                    currentPermission();
                    $("input[name=permission]").val(currentPermission());
                });
            });
            // if ($('#rep_check > input:checked').length == $('#rep_check > input').length) {
            //     $('#rep').prop('checked', true);
            // }
        })
    </script>
@endsection
