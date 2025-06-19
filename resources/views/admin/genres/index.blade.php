@extends('layouts.admin')
@section('title', 'Genre Management')
@section('content')
    <div class="mb-8">
        <h2>Genre Master</h2>
        <div class="table-container">
            <form method="GET" action="{{ route('genres.index') }}" class="flex justify-between mb-6">
                <div class="search-container">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" placeholder="Search Genres" value="{{ $search ?? '' }}">
                </div>
                <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addGenreModal">Add New Genre</button>
            </form>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Genre Name</th>
                        <th>Image</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($genres as $genre)
                        <tr>
                            <td>{{ $genre->id }}</td>
                            <td>{{ $genre->genre_name }}</td>
                            <td>
                                @if($genre->image)
                                    <img src="{{ asset('uploads/' . $genre->image) }}" alt="{{ $genre->genre_name }}" class="h-12 w-12 object-cover">
                                @else
                                    No Image
                                @endif
                            </td>
                            <td>{{ $genre->description ?? '-' }}</td>
                            <td>{{ $genre->status }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#editGenreModal{{ $genre->id }}"><i class="fas fa-edit"></i></button>
                                <form action="{{ route('genres.destroy', $genre->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete();">
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
                <p>Showing {{ $genres->count() }} of {{ $genres->total() }} entries</p>
                {{ $genres->links() }}
            </div>
        </div>
    </div>

    <!-- Add Genre Modal -->
    <div class="modal fade" id="addGenreModal" tabindex="-1" aria-labelledby="addGenreModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addGenreModalLabel">Add New Genre</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
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
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Genre Modals -->
    @foreach($genres as $genre)
        <div class="modal fade" id="editGenreModal{{ $genre->id }}" tabindex="-1" aria-labelledby="editGenreModalLabel{{ $genre->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editGenreModalLabel{{ $genre->id }}">Edit Genre</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('genres.update', $genre->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <input type="text" id="genreName{{ $genre->id }}" name="genre_name" placeholder=" " value="{{ $genre->genre_name }}">
                                <label for="genreName{{ $genre->id }}">Genre Name</label>
                                @error('genre_name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <input type="file" id="genreImage{{ $genre->id }}" name="image" placeholder=" " accept="image/*">
                                <label for="genreImage{{ $genre->id }}">Image</label>
                                @if($genre->image)
                                    <p>Current: <a href="{{ asset('uploads/' . $genre->image) }}" target="_blank">View Image</a></p>
                                @endif
                                @error('image') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <textarea id="description{{ $genre->id }}" name="description" placeholder=" " rows="3">{{ $genre->description }}</textarea>
                                <label for="description{{ $genre->id }}">Description</label>
                            </div>
                            <div class="form-group">
                                <select id="genreStatus{{ $genre->id }}" name="status" placeholder=" ">
                                    <option value="active" {{ $genre->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $genre->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                <label for="genreStatus{{ $genre->id }}">Status</label>
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
        function confirmDelete() {
            return confirm('Are you sure you want to delete this genre?');
        }
    </script>
@endsection
