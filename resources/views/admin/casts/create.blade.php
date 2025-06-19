@extends('layouts.admin')
@section('title', 'Create Cast')
@section('content')
    <div class="mb-8">
        <h2>Create Cast</h2>
        <div class="table-container">
            <form action="{{ route('casts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <input type="text" id="castName" name="cast_name" placeholder=" " value="{{ old('cast_name') }}">
                    <label for="castName">Cast Name</label>
                    @error('cast_name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <input type="file" id="castImage" name="image" placeholder=" " accept="image/*">
                    <label for="castImage">Image</label>
                    @error('image') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <textarea id="description" name="description" placeholder=" " rows="3">{{ old('description') }}</textarea>
                    <label for="description">Description</label>
                </div>
                <div class="form-group">
                    <select id="castStatus" name="status" placeholder=" ">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <label for="castStatus">Status</label>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="{{ route('casts.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection