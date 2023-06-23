@extends('adminlte::page')

@section('title', 'Users | Dashboard')

@section('content_header')
    <h1>Users</h1>
@stop

@section('content')
    <div class="container-fliud">
        <div class="row">
            <div id="error-message"></div>
            <div class="col-md-3">
                <div class="card card-gray">
                    <form method="POST"action="{{ route('users.store') }}">

                        @csrf
                        <div class="card-header">
                            <h3 class="card-title">Add New</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="name"
                                    placeholder="Enter Full Name" value="{{ old('name') }}">
                                @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="email">Email <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="email" id="email"
                                    placeholder="Enter Users Email" value="{{ old('email') }}">
                                @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="password">Password <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="password" id="password"
                                    placeholder="Enter Users password" value="{{ old('password') }}">
                                @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="password">Roles <span class="text-danger">*</span></label>
                                <select name="roles[]" class="form-control select2" multiple="multiple"
                                    data-placeholder="Select Roles" id="select2">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                    @endforeach
                                </select>
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
                        <div class="table-responsive">
                            <table id="table-data"
                                class="table table-bordered table-striped dataTable dtr-inline collapsed">
                                <thead>
                                    <th>ID</th>
                                    <th>Name </th>
                                    <th>Email</th>
                                    <th>Date</th>
                                    <th>Roles</th>
                                    <th>Action</th>
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
        $(function() {
            $('#select2').select2();
        });
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
                ajax: "{{ route('users.index') }}",
                columns: [{
                    data: 'id',
                    name: 'id'
                }, {
                    data: 'name',
                    name: 'name'
                }, {
                    data: 'email',
                    name: 'email'
                }, {
                    data: 'date',
                    name: 'date'
                }, {
                    data: 'roles',
                    name: 'roles'
                }, {
                    data: 'action',
                    name: 'action',
                    bsortable: false,
                    className: "text-center"
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
                    var route = "{{ route('users.destroy', ':id') }}";
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
@section('plugins.Select2', true)
