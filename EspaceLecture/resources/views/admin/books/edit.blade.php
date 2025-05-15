@extends('layouts.admin')
@section('title', 'Edit Book')

@section('content')
    <div class="container">
        <h1 class="mb-4">Edit Book: {{ $book->title }}</h1>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.books.update', $book) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Title *</label>
                                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $book->title) }}" required>
                                @error('title')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="author">Author *</label>
                                <input type="text" name="author" id="author" class="form-control @error('author') is-invalid @enderror" value="{{ old('author', $book->author) }}" required>
                                @error('author')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="summary">Summary</label>
                        <textarea name="summary" id="summary" class="form-control @error('summary') is-invalid @enderror" rows="3">{{ old('summary', $book->summary) }}</textarea>
                        @error('summary')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="pages">Number of Pages *</label>
                                <input type="number" name="pages" id="pages" class="form-control @error('pages') is-invalid @enderror" value="{{ old('pages', $book->pages) }}" required min="1">
                                @error('pages')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="published_at">Published Date *</label>

                                    <input type="date" name="published_at" id="published_at" class="form-control @error('published_at') is-invalid @enderror" value="{{ old('published_at', \Carbon\Carbon::parse($book->published_at)->format('Y-m-d')) }}" required>                                @error('published_at')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="category_id">Category *</label>
                                <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Status *</label>
                                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                    <option value="available" {{ old('status', $book->status) === 'available' ? 'selected' : '' }}>Available</option>
                                    <option value="borrowed" {{ old('status', $book->status) === 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                                </select>
                                @error('status')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cover">Cover Image</label>
                                
                                @if($book->cover)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $book->cover) }}" alt="Current cover" width="100" class="img-thumbnail">
                                        <div class="form-check mt-2">
                                            <input type="checkbox" name="remove_cover" id="remove_cover" class="form-check-input">
                                            <label for="remove_cover" class="form-check-label">Remove current cover</label>
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="custom-file">
                                    <input type="file" name="cover" id="cover" class="custom-file-input @error('cover') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg">
                                    <label class="custom-file-label" for="cover">Choose new file</label>
                                </div>
                                <small class="form-text text-muted">Max 2MB (JPEG, PNG, JPG). Leave empty to keep current.</small>
                                @error('cover')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Book
                        </button>
                        <a href="{{ route('admin.books.index') }}" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Update the file input label with the selected file name
        document.querySelector('.custom-file-input').addEventListener('change', function(e) {
            var fileName = e.target.files[0] ? e.target.files[0].name : "Choose file";
            e.target.nextElementSibling.textContent = fileName;
        });
    </script>
@endpush