@extends('adminlte::page')

@section('title', 'Permissions | Dashboard')

@section('content_header')
    <h1>Permissions</h1>
@stop

@section('content')
    <div class="container-fliud">
        <div class="row">
            <div id="error-message"></div>
            <div class="col-md-3">
                <div class="card card-gray">
                    <form method="POST"action="{{ route('users.permissions.store') }}">

                        @csrf
                        <div class="card-header">
                            <h3 class="card-title">Add New</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Permissions <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="name"
                                    placeholder="Enter Permission Name" value="{{ old('name') }}">
                                @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>

                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h3>List</h3>
                    </div>
                    <div class="card-body">
                        {{-- Datatables --}}
                        <div class="table-response">
                            <table id="table-data"
                                class="table table-bordered table-striped dataTable dtr-inline collapsed">
                                <thead>
                                    <th>ID</th>
                                    <th>NAME</th>
                                    <th>GUARD</th>
                                    <th>ACTION</th>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">

                    </div>
                </div>
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
                ajax: "{{ route('users.permissions.index') }}",
                columns: [{
                    data: 'id',
                    name: 'id'
                }, {
                    data: 'name',
                    name: 'name'
                }, {
                    data: 'guard_name',
                    name: 'guard_name'
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
