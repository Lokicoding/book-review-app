@extends('layouts.app')

@section('main')
<div class="container">
    <div class="row my-5">
        <div class="col-md-3">
            @include('layouts.sidebar')
        </div>
        <div class="col-md-9">
            @include('layouts.message')
            <div class="card border-0 shadow">
                <div class="card-header  text-white">
                    Update Book
                </div>
                <div class="card-body">
                    <form action="{{ route('books.update',$book->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                placeholder="Title" value="{{ old('title',$book->title) }}" name="title" id="title" />
                            @error('title')
                            <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="author" class="form-label">Author</label>
                            <input type="text" class="form-control @error('author') is-invalid @enderror"
                                placeholder="Author" value="{{ old('author',$book->author) }}" name="author" id="author" />
                            @error('author')
                            <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="author" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" placeholder="Description"
                                cols="30" rows="5">{{ old('description',$book->description) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="Image" class="form-label">Image</label>
                            <input type="file" accept=".jpg, .jpeg, .png" class="form-control @error('image') is-invalid @enderror" name="image"
                                id="image" />
                            @if (!empty($book->image))
                                <img src="{{ asset('uploads/books/thumb/'.$book->image) }}" class="w-25">
                            @endif    
                            @error('image')
                            <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="author" class="form-label">Status</label>
                            <select name="status" id="status"
                                class="form-control @error('status') is-invalid @enderror">
                                <option value="0" {{ ($book->status == 0 ? 'selected' : '') }}>Disable</option>
                                <option value="1" {{ ($book->status == 1 ? 'selected' : '') }}>Active</option>
                            </select>
                            @error('status')
                            <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <button class="btn btn-primary mt-2">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection