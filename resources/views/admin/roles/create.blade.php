@extends('layouts.admin')
@section('title', 'Create Role')
@section('content')
    <div class="mb-8">
        <h2>Create Role</h2>
        <div class="table-container">
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <input type="text" id="roleName" name="role_name" placeholder=" " value="{{ old('role_name') }}">
                    <label for="roleName">Role Name</label>
                    @error('role_name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <textarea id="roleDescription" name="description" placeholder=" " rows="3">{{ old('description') }}</textarea>
                    <label for="roleDescription">Description</label>
                </div>
                <div class="form-group">
                    <select id="roleStatus" name="status" placeholder=" ">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <label for="roleStatus">Status</label>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection