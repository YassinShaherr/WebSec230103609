@extends('layouts.master')
@section('title', 'Multiplication Table')
@section('content')
    <div class="container mt-5">
        <h2 class="text-center">Multiplication Table</h2>
        <p class="lead text-center">Here you can find a multiplication table.</p>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th></th>
                    @for ($i = 1; $i <= 10; $i++)
                        <th>{{ $i }}</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @for ($i = 1; $i <= 10; $i++)
                    <tr>
                        <th>{{ $i }}</th>
                        @for ($j = 1; $j <= 10; $j++)
                            <td>{{ $i * $j }}</td>
                        @endfor
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
@endsection