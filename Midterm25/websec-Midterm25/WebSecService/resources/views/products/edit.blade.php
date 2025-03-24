// resources/views/products/edit.blade.php
@extends('layouts.master')
@section('title', 'Edit Product')
@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">{{ $product->exists ? 'Edit' : 'Create' }} Product</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('products_save', $product) }}" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="code" class="form-label">Code</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" 
                               id="code" name="code" value="{{ old('code', $product->code) }}" required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Repeat for other fields -->
                    <div class="col-md-6">
                        <label for="photo" class="form-label">Product Image</label>
                        <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                               id="photo" name="photo">
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if ($product->photo)
                            <img src="{{ asset($product->photo) }}" alt="Product Image" 
                                 class="mt-2 img-thumbnail" style="max-height: 200px;">
                        @endif
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Save</button>
            </form>
        </div>
    </div>
</div>
@endsection