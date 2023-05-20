@extends('adminlte::page')

@section('title', 'Create Roles | Dashboard')

@section('content_header')
    <h1>Create Roles</h1>
@stop

@section('content')
    <div class="container-fliud">
        <div id="error-message"></div>
        <form action="{{ route('users.roles.store') }}" method="post">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Roles <span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="E.g. Manager"
                            value="{{ old('name') }}">
                        @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                    {{-- Datatables --}}
                    <label for="name">Assign Permissions <span class="text-danger"> *</span></label>
                    <div class="table-response">
                        <table id="table-data" class="table table-bordered table-striped dataTable dtr-inline collapsed">
                            <thead>
                                <th>
                                    <input class="permissions" type="checkbox" name="all_permission" id="all_permission">
                                </th>
                                <th>NAME</th>
                                <th>GUARD</th>
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
            $('[name="all_permission"]').on('click', function() {
                if ($(this).is(":checked")) {
                    $.each($('.permissions'), function() {
                        $(this).prop('checked', true);
                    });
                } else {
                    $.each($('.permissions'), function() {
                        $(this).prop('checked', false);
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
                ajax: "{{ route('users.permissions.index') }}",
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
                ],

            });

        })
    </script>
@stop

@section('plugins.Datatables', true)
