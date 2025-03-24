@extends('layouts.master')
@section('title', 'Prime Numbers')
@section('content')

<form action="{{route('products_save', $product->id ?? null)}}" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    @foreach($errors->all() as $error)
    <div class="alert alert-danger">
    <strong>Error!</strong> {{$error}}
    </div>
    @endforeach
    <div class="row mb-2">
        <div class="col-6">
            <label for="code" class="form-label">Code:</label>
            <input type="text" class="form-control" placeholder="Code" name="code" required value="{{$product->code ?? ''}}">
        </div>
        <div class="col-6">
            <label for="model" class="form-label">Model:</label>
            <input type="text" class="form-control" placeholder="Model" name="model" required value="{{$product->model ?? ''}}">
        </div>
    </div>
    <div class="row mb-2">
        <div class="col">
            <label for="name" class="form-label">Name:</label>
            <input type="text" class="form-control" placeholder="Name" name="name" required value="{{$product->name ?? ''}}">
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-6">
            <label for="price" class="form-label">Price:</label>
            <input type="number" step="0.01" class="form-control" placeholder="Price" name="price" required value="{{$product->price ?? ''}}">
        </div>
        <div class="col-6">
            <label for="photo" class="form-label">Photo:</label>
            <input type="file" class="form-control" name="photo" accept="image/jpeg,image/png,image/jpg,image/gif">
            @if(isset($product) && isset($product->photo) && $product->photo)
                <div class="mt-2">
                    <p>Current photo: {{$product->photo}}</p>
                </div>
            @endif
        </div>
    </div>
    <div class="row mb-2">
        <div class="col">
            <label for="name" class="form-label">Description:</label>
            <textarea type="text" class="form-control" placeholder="Description" name="description" required>{{$product->description ?? ''}}</textarea>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
@endsection
