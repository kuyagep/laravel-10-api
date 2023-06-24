@extends('adminlte::page')

@section('title', 'Update Users | Dashboard')

@section('content_header')
    <h1>Update Users</h1>
@stop

@section('content')
    <div class="container-fliud">
        <div class="row">
            <div id="error-message"></div>
            <div class="col-md-3">
                <div class="card card-gray">
                    <form method="POST"action="{{ route('users.update', $user->id) }}">
                        @method('patch')
                        @csrf
                        <div class="card-header">
                            <h3 class="card-title">Update User</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="name"
                                    placeholder="Enter Full Name" value="{{ $user->name }}">
                                @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="email">Email <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="email" id="email"
                                    placeholder="Enter Users Email" value="{{ $user->email }}">
                                @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="password">Password <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="password" id="password"
                                    placeholder="Enter Users password" value="12345678">
                                @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="password">Roles <span class="text-danger">*</span></label>
                                <select name="roles[]" class="form-control select2" multiple="multiple"
                                    data-placeholder="Select Roles" id="select2">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ $user->id ? (in_array($role->name, $userRole) ? 'selected' : '') : '' }}>
                                            {{ ucfirst($role->name) }}</option>
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
    </script>
@stop

@section('plugins.Select2', true)
