@extends('layouts.admin')
@section('title', 'Create Genre')
@section('content')
    <div class="mb-8">
        <h2>Create Genre</h2>
        <div class="table-container">
            <form action="{{ route('genres.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <input type="text" id="genreName" name="genre_name" placeholder=" " value="{{ old('genre_name') }}">
                    <label for="genreName">Genre Name</label>
                    @error('genre_name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <input type="file" id="genreImage" name="image" placeholder=" " accept="image/*">
                    <label for="genreImage">Image</label>
                    @error('image') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <textarea id="description" name="description" placeholder=" " rows="3">{{ old('description') }}</textarea>
                    <label for="description">Description</label>
                </div>
                <div class="form-group">
                    <select id="genreStatus" name="status" placeholder=" ">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <label for="genreStatus">Status</label>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="{{ route('genres.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection