@extends('layouts.master')
@section('title', 'Even Numbers')
@section('content')
    <div class="container mt-5">
        <h2 class="text-center">Even Numbers</h2>
        <p class="lead text-center">Here you can find a list of even numbers up to 100.</p>
        <div class="card m-4">
            <div class="card-body">
                @foreach (range(1, 100) as $i)
                    @if ($i % 2 == 0)
                        <span class="badge bg-primary m-1">{{ $i }}</span>
                    @else
                        <span class="badge bg-secondary m-1">{{ $i }}</span>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endsection