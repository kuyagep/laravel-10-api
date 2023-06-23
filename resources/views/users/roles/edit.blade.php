@extends('adminlte::page')

@section('title', 'Update Roles | Dashboard')

@section('content_header')
    <h1>Update Roles</h1>
@stop

@section('content')
    <div class="container-fliud">
        <div id="error-message"></div>
        <form action="{{ route('users.roles.update', $role->id) }}" method="post">
            @method('patch')
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="name" class="form-label">Name <span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="E.g. Manager"
                            value="{{ ucfirst($role->name) }}">
                        @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                    <label for="name">Assign Permissions <span class="text-danger"> *</span></label>
                    {{-- Datatables --}}
                    <div class="table-responsive">
                        <table id="table-data" class="table table-bordered table-striped dataTable dtr-inline collapsed">
                            <thead>
                                <tr>
                                    <th>
                                        <input class="permissions" type="checkbox" name="all_permission"
                                            id="all_permission">
                                    </th>
                                    <th>NAME</th>
                                    <th>GUARD</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Save Role</button>
                </div>
            </div>
        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            $('[name="all_permission"]').on("click", function() {
                if ($(this).is(":checked")) {
                    $.each($('.permission'), function() {
                        if ($(this).val() != "dashboard") {
                            $(this).prop('checked', true);
                        }

                    });
                } else {
                    $.each($('.permission'), function() {
                        if ($(this).val() != "dashboard") {
                            $(this).prop('checked', false);
                        }
                    });
                }
            });
            var table = $('#table-data').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                bPaginate: false,
                bFilter: false,
                ajax: "{{ route('users.permissions.index', ['role_id' => $role->id]) }}",
                columns: [{
                    data: 'chkBox',
                    name: 'chkBox',
                    orderable: false
                }, {
                    data: 'name',
                    name: 'name'
                }, {
                    data: 'guard_name',
                    name: 'guard_name'
                }, ],
                order: [
                    [0, "desc"]
                ]
            });
        });
    </script>
@stop

@section('plugins.Datatables', true)
