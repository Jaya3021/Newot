@extends('layouts.admin')
@section('title', 'Edit Content')

@section('content')
<div class="modal-body bg-dark text-white p-4 rounded shadow">
    <form action="{{ route('contents.update', $contentMaster->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label>Movie Name</label>
            <input type="text" name="movie_name" value="{{ $contentMaster->movie_name }}" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label>Genre</label>
            <select name="genre_id" class="form-control" required>
                @foreach($genres as $genre)
                    <option value="{{ $genre->id }}" {{ $contentMaster->genre_id == $genre->id ? 'selected' : '' }}>
                        {{ $genre->genre_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Cast Members and Their Roles</label>
            <div id="castSection">
                <div class="row">
                    @foreach($castRoles as $pair)
                        <div class="col-md-6 mb-2">
                            <select name="cast_id[]" class="form-control">
                                <option value="">-- Select Cast --</option>
                                @foreach($casts as $cast)
                                    <option value="{{ $cast->id }}" {{ $pair['cast_id'] == $cast->id ? 'selected' : '' }}>
                                        {{ $cast->cast_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <select name="role_id[]" class="form-control">
                                <option value="">-- Select Role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ $pair['role_id'] == $role->id ? 'selected' : '' }}>
                                        {{ $role->role_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="form-group mb-3">
            <label>Thumbnail</label>
            <input type="file" name="thumbnail" class="form-control">
            @if($contentMaster->thumbnail)
                <p>Current: <a href="{{ asset('storage/' . $contentMaster->thumbnail) }}" target="_blank">View</a></p>
            @endif
        </div>

        <div class="form-group mb-3">
            <label>Trailer URL</label>
            <input type="text" name="trailer_url" value="{{ $contentMaster->trailer_url }}" class="form-control">
        </div>

        <div class="form-group mb-3">
            <label>Full Video</label>
            <input type="file" name="full_video" class="form-control">
            @if($contentMaster->full_video_url)
                <p>Current: <a href="{{ $contentMaster->full_video_url }}" target="_blank">View</a></p>
            @endif
        </div>

        <div class="form-group mb-3">
            <label>Release Year</label>
            <input type="number" name="release_year" value="{{ $contentMaster->release_year }}" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ $contentMaster->description }}</textarea>
        </div>

        <div class="form-group mb-3">
            <label>Language</label>
            <input type="text" name="language" value="{{ $contentMaster->language }}" class="form-control">
        </div>

        <div class="form-group mb-3">
            <label>Duration (in minutes)</label>
            <input type="number" name="duration" value="{{ $contentMaster->duration }}" class="form-control">
        </div>

        <div class="form-group mb-3">
            <label>Content Rating</label>
            <select name="content_rating" class="form-control">
                <option value="" disabled {{ !$contentMaster->content_rating ? 'selected' : '' }}>Select Rating</option>
                <option value="G" {{ $contentMaster->content_rating == 'G' ? 'selected' : '' }}>G</option>
                <option value="PG" {{ $contentMaster->content_rating == 'PG' ? 'selected' : '' }}>PG</option>
                <option value="PG-13" {{ $contentMaster->content_rating == 'PG-13' ? 'selected' : '' }}>PG-13</option>
                <option value="R" {{ $contentMaster->content_rating == 'R' ? 'selected' : '' }}>R</option>
                <option value="NC-17" {{ $contentMaster->content_rating == 'NC-17' ? 'selected' : '' }}>NC-17</option>
            </select>
        </div>

        <div class="form-group mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="active" {{ $contentMaster->status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $contentMaster->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('contents.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
