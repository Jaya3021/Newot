@extends('layouts.admin')
@section('title', 'Cast Management')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <div class="mb-8">
        <h2>Cast Master</h2>
        <div class="table-container">
            <form method="GET" action="{{ route('casts.index') }}" class="flex justify-between mb-6">
                <div class="search-container">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" placeholder="Search Cast" value="{{ $search ?? '' }}">
                </div>
                <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addCastModal">Add New Cast</button>
            </form>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cast Name</th>
                        <th>Image</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($casts as $cast)
                        <tr>
                            <td>{{ $cast->id }}</td>
                            <td>{{ $cast->cast_name }}</td>
                            <td>
                                @if($cast->image)
                                    <img src="{{ asset('uploads/' . $cast->image) }}" alt="{{ $cast->cast_name }}" class="h-12 w-12 object-cover">
                                @else
                                    No Image
                                @endif
                            </td>
                            <td>{{ $cast->description ?? '-' }}</td>
                            <td>{{ $cast->status }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#editCastModal{{ $cast->id }}"><i class="fas fa-edit"></i></button>
                                <form id="delete-form-{{ $cast->id }}" action="{{ route('casts.destroy', $cast->id) }}" method="POST" style="display:inline;" >
                                    @csrf
                                    @method('DELETE')
                                    
                                </form>

                                <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="{{ $cast->id }}"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="flex justify-between items-center mt-4">
                <p>Showing {{ $casts->count() }} of {{ $casts->total() }} entries</p>
                {{ $casts->links() }}
            </div>
        </div>
    </div>

    <!-- Add Cast Modal -->
    <div class="modal fade" id="addCastModal" tabindex="-1" aria-labelledby="addCastModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCastModalLabel">Add New Cast</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
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
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Cast Modals -->
    @foreach($casts as $cast)
        <div class="modal fade" id="editCastModal{{ $cast->id }}" tabindex="-1" aria-labelledby="editCastModalLabel{{ $cast->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCastModalLabel{{ $cast->id }}">Edit Cast</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('casts.update', $cast->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <input type="text" id="castName{{ $cast->id }}" name="cast_name" placeholder=" " value="{{ $cast->cast_name }}">
                                <label for="castName{{ $cast->id }}">Cast Name</label>
                                @error('cast_name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <input type="file" id="castImage{{ $cast->id }}" name="image" placeholder=" " accept="image/*">
                                <label for="castImage{{ $cast->id }}">Image</label>
                                @if($cast->image)
                                    <p>Current: <a href="{{ asset('uploads/' . $cast->image) }}" target="_blank">View Image</a></p>
                                @endif
                                @error('image') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <textarea id="description{{ $cast->id }}" name="description" placeholder=" " rows="3">{{ $cast->description }}</textarea>
                                <label for="description{{ $cast->id }}">Description</label>
                            </div>
                            <div class="form-group">
                                <select id="castStatus{{ $cast->id }}" name="status" placeholder=" ">
                                    <option value="active" {{ $cast->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $cast->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                <label for="castStatus{{ $cast->id }}">Status</label>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
       
        const deleteButtons = document.querySelectorAll('.delete-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const contentId = this.getAttribute('data-id');
 console.log("hello keshav");
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + contentId).submit();
                    }
                });
            });
        });
    });
</script>



    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this cast?');
        }
    </script>
@endsection
