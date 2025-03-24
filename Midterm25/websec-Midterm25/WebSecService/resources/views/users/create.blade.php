@extends('layouts.master')
@section('title', 'Create User')
@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
  $("#clean_permissions").click(function(){
    $('#permissions').val([]);
  });
  $("#clean_roles").click(function(){
    $('#roles').val([]);
  });
});
</script>
<div class="d-flex justify-content-center">
    <div class="row m-4 col-sm-8">
        <h2>Create New User</h2>
        <form action="{{route('users_store')}}" method="post">
            {{ csrf_field() }}
            @foreach($errors->all() as $error)
            <div class="alert alert-danger">
            <strong>Error!</strong> {{$error}}
            </div>
            @endforeach
            <div class="row mb-2">
                <div class="col-12">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" class="form-control" placeholder="Name" name="name" required value="{{ old('name') }}">
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-12">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" placeholder="Email" name="email" required value="{{ old('email') }}">
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-12">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" class="form-control" placeholder="Password" name="password" required>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-12">
                    <label for="password_confirmation" class="form-label">Confirm Password:</label>
                    <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" required>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-12">
                    <label for="credit" class="form-label">Initial Credit:</label>
                    <input type="number" step="0.01" min="0" class="form-control" placeholder="Credit Amount" name="credit" value="0.00">
                </div>
            </div>
            <div class="col-12 mb-2">
                <label for="roles" class="form-label">Roles:</label> (<a href='#' id='clean_roles'>reset</a>)
                <select multiple class="form-select" id='roles' name="roles[]">
                    @foreach($roles as $role)
                    <option value='{{$role->name}}'>
                        {{$role->name}}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 mb-2">
                <label for="permissions" class="form-label">Direct Permissions:</label> (<a href='#' id='clean_permissions'>reset</a>)
                <select multiple class="form-select" id='permissions' name="permissions[]">
                @foreach($permissions as $permission)
                    <option value='{{$permission->name}}'>
                        {{$permission->display_name}}
                    </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Create User</button>
        </form>
    </div>
</div>
@endsection 