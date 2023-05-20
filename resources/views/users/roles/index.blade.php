@extends('adminlte::page')

@section('title', 'Roles | Dashboard')

@section('content_header')
    <h1>Roles</h1>
@stop

@section('content')
    <div class="container-fliud">
        <div id="error-message"></div>
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h3>List</h3>
                </div>

                <a href="{{ route('users.roles.create') }}" class="float-right btn btn-primary btn-xs m-0">Add New</a>
            </div>

            <div class="card-body">
                {{-- Datatables --}}
                <div class="table-response">
                    <table id="table-data" class="table table-bordered table-striped dataTable dtr-inline collapsed">
                        <thead>
                            <th>ID</th>
                            <th>NAME</th>
                            <th>USERS</th>
                            <th>PERMISIONS</th>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="card-footer">

            </div>
        </div>

    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var table = $('#table-data').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: "{{ route('users.roles.index') }}",
                columns: [{
                    data: 'id',
                    name: 'id'
                }, {
                    data: 'name',
                    name: 'name'
                }, {
                    data: 'users_count',
                    name: 'users_count'
                }, {
                    data: 'permission_count ',
                    name: 'permission_count'
                }, {
                    data: 'action',
                    name: 'action'
                }, ],
                order: [
                    [0, "desc"]
                ],

            });

            $('body').on('click', '#btnDelete', function() {
                //confirmation
                var id = $(this).data('id');

                if (confirm('Delete Data ' + id + ' ?') == true) {
                    //execute delete
                    var route = "{{ route('users.permissions.destroy', ':id') }}";
                    route = route.replace(':id', id);
                    $.ajax({
                        url: route,
                        type: "delete",

                        success: function(res) {
                            $("#table-data").DataTable().ajax.reload();
                        },
                        error: function(res) {
                            $("#error-message").html(
                                '<div class="alert alert-danger">' + response.message +
                                '</div>');
                        }
                    });
                } else {
                    //do something
                }
            });
        })
    </script>
@stop

@section('plugins.Datatables', true)
