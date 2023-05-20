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
                    <form method="POST"action="{{ route('users.permissions.update', $permission->id) }}">
                        @method('patch')
                        @csrf
                        <div class="card-header">
                            <h3 class="card-title">Update</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Permissions <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="name"
                                    placeholder="Enter Permission Name" value="{{ $permission->name }}">
                                @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>

                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@stop
