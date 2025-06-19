@extends('layouts.admin')
@section('title', 'Edit Genre')
@section('content')
    <div class="mb-8">
        <h2>Edit Genre</h2>
        <div class="table-container">
            <form action="{{ route('genres.update', $genre->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <input type="text" id="genreName" name="genre_name" placeholder=" " value="{{ $genre->genre_name }}">
                    <label for="genreName">Genre Name</label>
                    @error('genre_name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <input type="file" id="genreImage" name="image" placeholder=" " accept="image/*">
                    <label for="genreImage">Image</label>
                    @if($genre->image) <p>Current: <a href="{{ asset('Uploads/' . $genre->image) }}" target="_blank">View Image</a></p> @endif
                    @error('image') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <textarea id="description" name="description" placeholder=" " rows="3">{{ $genre->description }}</textarea>
                    <label for="description">Description</label>
                </div>
                <div class="form-group">
                    <select id="genreStatus" name="status" placeholder=" ">
                        <option value="active" {{ $genre->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $genre->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <label for="genreStatus">Status</label>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="{{ route('genres.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection