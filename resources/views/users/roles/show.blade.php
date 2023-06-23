@extends('adminlte::page')

@section('title', 'Roles Details | Dashboard')

@section('content_header')
    <h1>Roles Details</h1>
@stop

@section('content')
    <div class="container-fliud">

        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label for="name" class="form-label">Name <span class="text-danger"> *</span></label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="E.g. Manager"
                        value="{{ ucfirst($role->name) }}" disabled>
                </div>
                <label for="name">Assign Permissions <span class="text-danger"> *</span></label>
                {{-- Datatables --}}
                <div class="table-responsive">
                    <table id="table-data" class="table table-bordered table-striped dataTable dtr-inline collapsed">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>NAME</th>
                                <th>GUARD</th>
                            </tr>
                        </thead>
                    </table>
                </div>
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

            var table = $('#table-data').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                bPaginate: false,
                bFilter: true,
                ajax: "{{ route('users.roles.show', $role->id) }}",
                columns: [{
                    data: 'id',
                    name: 'id'
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
