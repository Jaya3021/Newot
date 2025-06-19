@extends('layouts.admin')
@section('title', 'Edit Role')
@section('content')
    <div class="mb-8">
        <h2>Edit Role</h2>
        <div class="table-container">
            <form action="{{ route('roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <input type="text" id="roleName" name="role_name" placeholder=" " value="{{ $role->role_name }}">
                    <label for="roleName">Role Name</label>
                    @error('role_name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <textarea id="roleDescription" name="description" placeholder=" " rows="3">{{ $role->description }}</textarea>
                    <label for="roleDescription">Description</label>
                </div>
                <div class="form-group">
                    <select id="roleStatus" name="status" placeholder=" ">
                        <option value="active" {{ $role->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $role->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <label for="roleStatus">Status</label>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection