@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Book List</h1>
    <a href="{{ route('books.create') }}" class="btn btn-primary mb-3">Add New Book</a>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Published Year</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($books as $book)
                <tr>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->author }}</td>
                    <td>{{ $book->published_year }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection