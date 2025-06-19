@extends('layouts.admin')
@section('title', 'Edit Cast')
@section('content')
    <div class="mb-8">
        <h2>Edit Cast</h2>
        <div class="table-container">
            <form action="{{ route('casts.update', $cast->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <input type="text" id="castName" name="cast_name" placeholder=" " value="{{ $cast->cast_name }}">
                    <label for="castName">Cast Name</label>
                    @error('cast_name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <input type="file" id="castImage" name="image" placeholder=" " accept="image/*">
                    <label for="castImage">Image</label>
                    @if($cast->image) <p>Current: <a href="{{ asset('uploads/' . $cast->image) }}" target="_blank">View Image</a></p> @endif
                    @error('image') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <textarea id="description" name="description" placeholder=" " rows="3">{{ $cast->description }}</textarea>
                    <label for="description">Description</label>
                </div>
                <div class="form-group">
                    <select id="castStatus" name="status" placeholder=" ">
                        <option value="active" {{ $cast->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $cast->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <label for="castStatus">Status</label>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="{{ route('casts.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection