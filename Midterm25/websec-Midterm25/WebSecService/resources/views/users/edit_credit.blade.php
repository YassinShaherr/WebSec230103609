@extends('layouts.master')
@section('title', 'Edit User Credit')
@section('content')
<div class="d-flex justify-content-center">
    <div class="row m-4 col-sm-8">
        <h2>Add Credit for {{$user->name}}</h2>
        <p>Current Credit: ${{number_format($user->credit, 2)}}</p>
        
        <form action="{{route('add_credit', $user->id)}}" method="post">
            {{ csrf_field() }}
            @foreach($errors->all() as $error)
            <div class="alert alert-danger">
                <strong>Error!</strong> {{$error}}
            </div>
            @endforeach

            <div class="row mb-2">
                <div class="col-12">
                    <label class="form-label">Amount to Add:</label>
                    <input type="number" step="0.01" min="0.01" class="form-control" placeholder="Amount" name="amount" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Add Credit</button>
        </form>
    </div>
</div>
@endsection 