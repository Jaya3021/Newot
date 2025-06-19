@extends('layouts.admin')
@section('title', 'Create Content')
@section('content')
    <div class="mb-8">
        <h2 class="text-2xl font-bold">Create Content</h2>
        <a href="{{ route('contents.index') }}" class="btn btn-secondary">Back to Content</a>
    </div>
    <div class="table-container p-6">
        <form action="{{ route('contents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <input type="text" name="movie_name" id="movie_name{{ rand() }}" placeholder=" " value="{{ old('movie_name') }}">
                <label for="movie_name{{ rand() }}">Movie Name</label>
                @error('movie_name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <select name="genre_id" id="genre_id{{ rand() }}">
                    <option value="">Select Genre</option>
                    @foreach($genres as $genre)
                        <option value="{{ $genre->id }}" {{ old('genre_id') == $genre->id ? 'selected' : '' }}>{{ $genre->genre_name }}</option>
                    @endforeach
                </select>
                <label for="genre_id{{ rand() }}">Genre</label>
                @error('genre_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <select name="cast_ids[]" id="cast_ids{{ rand() }}" multiple>
                    @foreach($casts as $cast)
                        <option value="{{ $cast->id }}" {{ in_array($cast->id, old('cast_ids', [])) ? 'selected' : '' }}>{{ $cast->cast_name }}</option>
                    @endforeach
                </select>
                <label for="cast_ids{{ rand() }}">Casts (Hold Ctrl to select multiple)</label>
                @error('cast_ids') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <input type="file" name="thumbnail" id="thumbnail{{ rand() }}" placeholder=" " accept="image/*">
                <label for="thumbnail{{ rand() }}">Thumbnail</label>
                @error('thumbnail') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <input type="url" name="trailer_url" id="trailer_url{{ rand() }}" placeholder=" " value="{{ old('trailer_url') }}">
                <label for="trailer_url{{ rand() }}">Trailer URL</label>
                @error('trailer_url') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <input type="number" name="release_year" id="release_year{{ rand() }}" placeholder=" " value="{{ old('release_year') }}">
                <label for="release_year{{ rand() }}">Release Year</label>
                @error('release_year') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <textarea name="description" id="description{{ rand() }}" placeholder=" ">{{ old('description') }}</textarea>
                <label for="description{{ rand() }}">Description</label>
                @error('description') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <input type="text" name="language" id="language{{ rand() }}" placeholder=" " value="{{ old('language') }}">
                <label for="language{{ rand() }}">Language</label>
                @error('language') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <input type="number" name="duration" id="duration{{ rand() }}" placeholder=" " value="{{ old('duration') }}">
                <label for="duration{{ rand() }}">Duration (minutes)</label>
                @error('duration') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <input type="text" name="content_rating" id="content_rating{{ rand() }}" placeholder=" " value="{{ old('content_rating') }}">
                <label for="content_rating{{ rand() }}">Content Rating</label>
                @error('content_rating') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <select name="status" id="status{{ rand() }}">
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                <label for="status{{ rand() }}">Status</label>
                @error('status') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn btn-primary">Create Content</button>
        </form>
    </div>
@endsection