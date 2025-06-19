@extends('layouts.admin')
@section('title', 'Role Management')
@section('content')
<div class="mb-8">
    <h2>Role Management</h2>
    <div class="table-container">
        <form method="GET" action="{{ route('roles.index') }}" class="flex justify-between mb-6">
           <div class="search-container"></div>
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Search Roles" type="text" value="{{ $search ?? '' }}">
        </div>
        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-toggle="modal" data-bs-target="#addRoleModal">Add New Role</button>
    </form>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Role</th>
                <th>NameRole Name</th>
                <th>Description</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td>{{ $role->role_name }}</td>
                    <td>{{ $role->description ?? '-' }}</td>
                    <td>{{ $role->status }}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#editRoleModal{{ $role->id }}"><i class="fas fa fa-edit"></i></button>
                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display: inline;" onsubmit="return confirmDelete();">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="flex justify-between items-center mt-4">
        <p>Showing {{ $roles->count() }} of {{ $roles->total() }} entries</p>
        {{ $roles->links() }}
    </div>
</div>
</div>

<!-- Add Role Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRoleModalLabel">Add New Role</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Role Modals -->
@foreach($roles as $role)
    <div class="modal fade" id="editRoleModal{{ $role->id }}" tabindex="-1" aria-labelledby="editRoleModalLabel{{ $role->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRoleModalLabel{{ $role->id }}">Edit Role</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('roles.update', $role->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <input type="text" id="roleName{{ $role->id }}" name="role_name" placeholder=" " value="{{ $role->role_name }}">
                            <label for="roleName{{ $role->id }}">Role Name</label>
                            @error('role_name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <textarea id="roleDescription{{ $role->id }}" name="description" placeholder=" " rows="3">{{ $role->description }}</textarea>
                            <label for="roleDescription{{ $role->id }}">Description</label>
                        </div>
                        <div class="form-group">
                            <select id="roleStatus{{ $role->id }}" name="status" placeholder=" ">
                                <option value="active" {{ $role->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $role->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <label for="roleStatus{{ $role->id }}">Status</label>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

<!-- Confirm Delete Script -->
<script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete this role?');
    }
</script>
@endsection
