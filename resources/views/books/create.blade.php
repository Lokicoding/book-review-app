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
                    Add Book
                </div>
                <div class="card-body">
                    <form action="{{ route('books.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                placeholder="Title" value="{{ old('title') }}" name="title" id="title" />
                            @error('title')
                            <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="author" class="form-label">Author</label>
                            <input type="text" class="form-control @error('author') is-invalid @enderror"
                                placeholder="Author" value="{{ old('author') }}" name="author" id="author" />
                            @error('author')
                            <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="author" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" placeholder="Description"
                                cols="30" rows="5">{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="Image" class="form-label">Image</label>
                            <input type="file" accept=".jpg, .jpeg, .png" class="form-control @error('image') is-invalid @enderror" name="image"
                                id="image" />
                            @error('image')
                            <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="author" class="form-label">Status</label>
                            <select name="status" id="status"
                                class="form-control @error('status') is-invalid @enderror">
                                <option value="1">Active</option>
                                <option value="0">Block</option>
                            </select>
                            @error('status')
                            <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <button class="btn btn-primary mt-2">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection