@extends('layouts.admin')
@section('title', 'Books Management')

@section('content')
    <div class="container">
        <h1 class="mb-4">Books Management</h1>
        
        <div class="mb-4">
            <a href="{{ route('admin.books.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Book
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Cover</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($books as $book)
                                <tr>
                                    <td>
                                        @if($book->cover)
                                            <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->title }}" width="50" class="img-thumbnail">
                                        @else
                                            <span class="text-muted">No cover</span>
                                        @endif
                                    </td>
                                    <td>{{ $book->title }}</td>
                                    <td>{{ $book->author }}</td>
                                    <td>{{ $book->category->name ?? 'Uncategorized' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $book->status === 'available' ? 'success' : 'warning' }}">
                                            {{ $book->status }}
                                        </span>
                                    </td>
                                    <td>
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.books.destroy', $book) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this book?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No books found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center">
                    {{ $books->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection