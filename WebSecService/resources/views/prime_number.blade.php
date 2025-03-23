@extends('layouts.master')
@section('title', 'Prime Numbers')
@section('content')
    <div class="container mt-5">
        <h2 class="text-center">Prime Numbers</h2>
        <p class="lead text-center">Here you can find a list of prime numbers.</p>
        <ul class="list-group">
            @foreach ([2, 3, 5, 7, 11, 13, 17, 19, 23, 29] as $prime)
                <li class="list-group-item">{{ $prime }}</li>
            @endforeach
        </ul>
    </div>
@endsection