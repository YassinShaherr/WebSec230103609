@extends('layouts.master')
@section('title', 'Multitable Numbers')
@section('content')

    <div class="card m-4">
        <div class="card-header">Multitable table of {{ $j }}</div>
        <div class="card-body">
            @foreach (range(1, 100) as $i)
                @if(isPrime($i))
                    <span class="badge bg-primary">{{$i}}</span>
                @else
                    <span class="badge bg-secondary">{{$i}}</span>
                @endif
            @endforeach
        </div>
    </div>
@endsection
