@extends('layouts.admin')
@section('title', 'Content Management')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="mb-8">
        <h2>Content Master</h2>
        <div class="table-container">
            <form method="GET" action="{{ route('contents.index') }}" class="flex justify-between mb-6">
                <div class="search-container">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" placeholder="Search Content" value="{{ $search ?? '' }}">
                </div>
                <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addContentModal">Add
                    New Content</button>
            </form>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Movie Name</th>
                        <th>Genre</th>
                        <th>Cast</th>
                        <th>Thumbnail</th>
                        <th>Trailer</th>
                        <th>Full Video</th>
                        <th>Release Year</th>
                        <th>Content Rating</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contents as $content)
                        <tr>
                            <td>{{ $content->id }}</td>
                            <td>{{ $content->movie_name }}</td>
                            <td>{{ $content->genre->genre_name }}</td>
                            <td>
                                @if($content->castMembers->isNotEmpty())
                                    {{ $content->castMembers->map(fn($cast) => $cast->cast_name . ' as ' . $cast->pivot->role_name)->implode(', ') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($content->thumbnail)
                                    <img src="{{ asset('storage/' . $content->thumbnail) }}" alt="{{ $content->movie_name }}" style="width: 100px;
                                                height: auto;" class="h-12 w-12 object-cover">
                                @else
                                    No Thumbnail
                                @endif
                            </td>
                            <td><a href="{{ $content->trailer_url ?? '#' }}" class="text-red-600 hover:underline"
                                    target="_blank">{{ $content->trailer_url ? 'Watch Trailer' : '-' }}</a></td>
                            <td>
                                @if($content->full_video_url)
                                    <a href="{{ $content->full_video_url }}" class="text-blue-600 hover:underline"
                                        target="_blank">View Video</a>
                                @else
                                    No Video
                                @endif
                            </td>
                            <td>{{ $content->release_year }}</td>
                            <td>{{ $content->content_rating ?? '-' }}</td>
                            <td>{{ $content->status ? 'Active' : 'Inactive' }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal"
                                    data-bs-target="#editContentModal{{ $content->id }}"><i class="fas fa-edit"></i></button>

                                <form id="delete-form-{{ $content->id }}"
                                    action="{{ route('contents.delete.db', $content->id) }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                <button type="button" class="btn btn-danger delete-btn" data-id="{{ $content->id }}">
                                    <i class="fa fa-trash"></i>
                                </button>


                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="flex justify-between items-center mt-4">
                <p>Showing {{ $contents->count() }} of {{ $contents->total() }} entries</p>
                {{ $contents->links() }}
            </div>
        </div>
    </div>

    <!-- Add Content Modal -->
    <div class="modal fade" id="addContentModal" tabindex="-1" aria-labelledby="addContentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('contents.store') }}" method="POST" enctype="multipart/form-data"
                    id="addContentForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addContentModalLabel">Add New Content</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="mb-3">
                            <label for="movie_name" class="form-label">Movie Name</label>
                            <input type="text" class="form-control" name="movie_name" value="{{ old('movie_name') }}"
                                required>
                            @error('movie_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="genre_id" class="form-label">Genre</label>
                            <select class="form-select" name="genre_id" required>
                                <option value="">Select Genre</option>
                                @foreach($genres as $genre)
                                    <option value="{{ $genre->id }}" {{ old('genre_id') == $genre->id ? 'selected' : '' }}>
                                        {{ $genre->genre_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('genre_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="thumbnail" class="form-label">Thumbnail</label>
                            <input type="file" class="form-control" name="thumbnail" accept="image/*" required>
                            @error('thumbnail')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="trailer_url" class="form-label">Trailer URL</label>
                            <input type="url" class="form-control" name="trailer_url" value="{{ old('trailer_url') }}">
                            @error('trailer_url')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="full_video" class="form-label">Full Video</label>
                            <input type="file" class="form-control" name="full_video" accept="video/*" required>
                            @error('full_video')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="release_year" class="form-label">Release Year</label>
                            <input type="number" class="form-control" name="release_year" value="{{ old('release_year') }}"
                                min="1900" max="{{ date('Y') }}" required>
                            @error('release_year')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3"
                                required>{{ old('description') }}</textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="language" class="form-label">Language</label>
                            <input type="text" class="form-control" name="language" value="{{ old('language') }}" required>
                            @error('language')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="duration" class="form-label">Duration (in minutes)</label>
                            <input type="number" class="form-control" name="duration" value="{{ old('duration') }}"
                                required>
                            @error('duration')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="content_rating" class="form-label">Content Rating</label>
                            <select class="form-select" name="content_rating" required>
                                <option value="" disabled selected>Select Rating</option>
                                <option value="G" {{ old('content_rating') == 'G' ? 'selected' : '' }}>G</option>
                                <option value="PG" {{ old('content_rating') == 'PG' ? 'selected' : '' }}>PG</option>
                                <option value="PG-13" {{ old('content_rating') == 'PG-13' ? 'selected' : '' }}>PG-13</option>
                                <option value="R" {{ old('content_rating') == 'R' ? 'selected' : '' }}>R</option>
                                <option value="NC-17" {{ old('content_rating') == 'NC-17' ? 'selected' : '' }}>NC-17</option>
                            </select>
                            @error('content_rating')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            {{--
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" name="status" required>
                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            --}}
                            <select name="status" class="form-control">
    <option value="active" {{ old('status', $contentMaster->status ?? '') == 1 ? 'selected' : '' }}>Active</option>
    <option value="inactive" {{ old('status', $contentMaster->status ?? '') == 0 ? 'selected' : '' }}>Inactive</option>
</select>

                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cast and Roles</label>
                        </div>
                        <div id="castSection">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="cast_id" class="form-label">Select Cast Members</label>
                                        <select class="form-select" name="cast_id[]" id="cast_id">
                                            <option value="" selected>Select Cast Member</option>
                                            @foreach($casts as $cast)
                                                <option value="{{ $cast->id }}">{{ $cast->cast_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('cast_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label for="role_id" class="form-label">Select Role of Member</label>
                                    <select class="form-select" name="role_id[]" id="role_id">
                                        <option value="" selected>Select Role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('role_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-lg-4 d-flex align-items-end">
                                    <div class="mb-3">
                                        <button type="button" id="addCastRoleButton"
                                            class="btn btn-primary me-2">Add</button>
                                        <button type="button" id="removeCastButton" class="btn btn-danger">Remove
                                            Last</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Content</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Content Modals -->
    <!-- Edit Content Modals -->
    @foreach($contents as $content)
        <div class="modal fade" id="editContentModal{{ $content->id }}" tabindex="-1"
            aria-labelledby="editContentModalLabel{{ $content->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editContentModalLabel{{ $content->id }}">Edit Content</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('contents.update', $content->id) }}" method="POST" enctype="multipart/form-data"
                            id="editContentForm{{ $content->id }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <input type="text" id="movieName{{ $content->id }}" name="movie_name" placeholder=" "
                                    value="{{ $content->movie_name }}" required>
                                <label for="movieName{{ $content->id }}">Movie Name</label>
                                @error('movie_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <select id="genreId{{ $content->id }}" name="genre_id" required>
                                    <option value="">Select Genre</option>
                                    @foreach($genres as $genre)
                                        <option value="{{ $genre->id }}" {{ $content->genre_id == $genre->id ? 'selected' : '' }}>
                                            {{ $genre->genre_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="genreId{{ $content->id }}">Genre</label>
                                @error('genre_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Cast and Roles</label>
                            </div>
                            <div id="editCastSection{{ $content->id }}">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="edit_cast_id{{ $content->id }}" class="form-label">Select Cast
                                                Members</label>
                                            <select class="form-select" name="edit_cast_id" id="edit_cast_id{{ $content->id }}">
                                                <option value="" selected>Select Cast Member</option>
                                                @foreach($casts as $cast)
                                                    <option value="{{ $cast->id }}">{{ $cast->cast_name }}</option>
                                                @endforeach
                                            </select>
                                            @error('edit_cast_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="edit_role_id{{ $content->id }}" class="form-label">Select Role of
                                            Member</label>
                                        <select class="form-select" name="edit_role_id" id="edit_role_id{{ $content->id }}">
                                            <option value="" selected>Select Role</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('edit_role_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4 d-flex align-items-end">
                                        <div class="mb-3">
                                            <button type="button" id="addEditCastRoleButton{{ $content->id }}"
                                                class="btn btn-primary me-2">Add</button>
                                            <button type="button" id="removeEditCastButton{{ $content->id }}"
                                                class="btn btn-danger">Remove Last</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="edit-cast-list{{ $content->id }}" class="mt-3">
                                @error('cast_role_pairs')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input type="file" id="thumbnail{{ $content->id }}" name="thumbnail" placeholder=" "
                                    accept="image/*">
                                <label for="thumbnail{{ $content->id }}">Thumbnail</label>
                                @if($content->thumbnail)
                                    <p>Current: <a href="{{ asset('storage/' . $content->thumbnail) }}" target="_blank">View
                                            Thumbnail</a></p>
                                @endif
                                @error('thumbnail')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input type="text" id="trailerUrl{{ $content->id }}" name="trailer_url" placeholder=" "
                                    value="{{ $content->trailer_url }}">
                                <label for="trailerUrl{{ $content->id }}">Trailer URL</label>
                                @error('trailer_url')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input type="file" id="fullVideo{{ $content->id }}" name="full_video" placeholder=" "
                                    accept="video/*">
                                <label for="fullVideo{{ $content->id }}">Full Video</label>
                                @if($content->full_video_url)
                                    <p>Current: <a href="{{ $content->full_video_url }}" target="_blank">View Video</a></p>
                                @endif
                                @error('full_video')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input type="number" id="releaseYear{{ $content->id }}" name="release_year" placeholder=" "
                                    value="{{ $content->release_year }}" required>
                                <label for="releaseYear{{ $content->id }}">Release Year</label>
                                @error('release_year')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <textarea id="description{{ $content->id }}" name="description" placeholder=" "
                                    rows="3">{{ $content->description }}</textarea>
                                <label for="description{{ $content->id }}">Description</label>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input type="text" id="language{{ $content->id }}" name="language" placeholder=" "
                                    value="{{ $content->language }}">
                                <label for="language{{ $content->id }}">Language</label>
                                @error('language')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input type="number" id="duration{{ $content->id }}" name="duration" placeholder=" "
                                    value="{{ $content->duration }}">
                                <label for="duration{{ $content->id }}">Duration (minutes)</label>
                                @error('duration')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <select id="contentRating{{ $content->id }}" name="content_rating" placeholder=" ">
                                    <option value="">Select Rating</option>
                                    <option value="G" {{ $content->content_rating == 'G' ? 'selected' : '' }}>G</option>
                                    <option value="PG" {{ $content->content_rating == 'PG' ? 'selected' : '' }}>PG</option>
                                    <option value="PG-13" {{ $content->content_rating == 'PG-13' ? 'selected' : '' }}>PG-13
                                    </option>
                                    <option value="R" {{ $content->content_rating == 'R' ? 'selected' : '' }}>R</option>
                                    <option value="NC-17" {{ $content->content_rating == 'NC-17' ? 'selected' : '' }}>NC-17
                                    </option>
                                </select>
                                <label for="contentRating{{ $content->id }}">Content Rating</label>
                                @error('content_rating')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                {{--
                                <select id="status{{ $content->id }}" name="status" required>
                                    <option value="active" {{ $content->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $content->status == 'inactive' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                                --}}
                                <label for="status{{ $content->id }}">Status</label>
                                @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            let castRolePairs = [];
            let rowCount = 1;

            const addButton = document.getElementById('addCastRoleButton');
            addButton.addEventListener('click', function (event) {
                event.stopPropagation();
                const currentRow = this.closest('.row');
                const castSelect = currentRow.querySelector('select[name="cast_id[]"]');
                const roleSelect = currentRow.querySelector('select[name="role_id[]"]');
                const castId = castSelect.value;
                const castName = castSelect.options[castSelect.selectedIndex].text;
                const roleId = roleSelect.value;
                const roleName = roleSelect.options[roleSelect.selectedIndex].text;

                if (castId && roleId) {
                    castRolePairs.push({ castId, roleId, castName, roleName });
                    updateCastRoleDisplay();

                    const originalRow = document.querySelector('#castSection .row');
                    const newRow = originalRow.cloneNode(true);
                    rowCount++;

                    newRow.querySelectorAll('select').forEach(element => {
                        if (element.name === 'cast_id[]' || element.name === 'role_id[]') {
                            element.name = element.name.replace(/\[\]$/, '_' + rowCount + '[]');
                        }
                    });

                    newRow.querySelector('select[name="cast_id_' + rowCount + '[]"]').selectedIndex = 0;
                    newRow.querySelector('select[name="role_id_' + rowCount + '[]"]').selectedIndex = 0;

                    const buttonContainer = newRow.querySelector('.d-flex');
                    buttonContainer.innerHTML = `
                                <div class="mb-3">
                                    <button type="button" id="removeCastButton_${rowCount}" class="btn btn-danger">Remove</button>
                                </div>
                            `;

                    originalRow.parentNode.appendChild(newRow);

                    newRow.querySelector('.btn-danger').addEventListener('click', function () {
                        newRow.remove();
                        rowCount--;
                        castRolePairs.pop();
                        updateCastRoleDisplay();
                        updateRemoveLastButtonVisibility();
                    });

                    updateRemoveLastButtonVisibility();

                    castSelect.selectedIndex = 0;
                    roleSelect.selectedIndex = 0;
                } else {
                    alert('Please select both a cast member and a role.');
                }
            });

            const removeButton = document.getElementById('removeCastButton');
            removeButton.addEventListener('click', function () {
                const rows = document.querySelectorAll('#castSection .row');
                if (rows.length > 1) {
                    rows[rows.length - 1].remove();
                    rowCount--;
                    castRolePairs.pop();
                    updateCastRoleDisplay();
                    updateRemoveLastButtonVisibility();
                }
            });

            function updateRemoveLastButtonVisibility() {
                removeButton.style.display = rowCount > 1 ? 'inline-block' : 'none';
            }
            updateRemoveLastButtonVisibility();

            function updateCastRoleDisplay() {
                let displayContainer = document.getElementById('cast-list');
                displayContainer.innerHTML = '';
                let hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'cast_role_pairs';
                displayContainer.appendChild(hiddenInput);

                if (castRolePairs.length > 0) {
                    const ul = document.createElement('ul');
                    ul.className = 'list-group';
                    castRolePairs.forEach((pair, index) => {
                        const li = document.createElement('li');
                        li.className = 'list-group-item d-flex justify-content-between align-items-center';
                        li.innerHTML = `
                                    ${pair.castName} as ${pair.roleName}
                                    <button type="button" class="btn btn-sm btn-danger remove-specific" data-index="${index}">Remove</button>
                                `;
                        ul.appendChild(li);
                    });
                    displayContainer.appendChild(ul);
                    hiddenInput.value = JSON.stringify(castRolePairs);

                    document.querySelectorAll('.remove-specific').forEach(button => {
                        button.addEventListener('click', function () {
                            const index = parseInt(this.getAttribute('data-index'));
                            castRolePairs.splice(index, 1);
                            updateCastRoleDisplay();
                            updateRemoveLastButtonVisibility();
                        });
                    });
                } else {
                    hiddenInput.value = '';
                }
            }

            const addContentForm = document.getElementById('addContentForm');
            addContentForm.addEventListener('submit', function (e) {
                console.log('Form submitted with cast_role_pairs:', document.querySelector('input[name="cast_role_pairs"]').value);
            });

            @foreach($contents as $content)
                let editCastRolePairs{{ $content->id }} = [
                    @foreach($content->castMembers as $cast)
                                                {
                            castId: '{{ $cast->id }}',
                            castName: '{{ $cast->cast_name }}',
                            roleId: '{{ $cast->pivot->role_id }}',
                            roleName: '{{ $cast->pivot->role_name }}'
                        },
                    @endforeach
                                ];
                let editRowCount{{ $content->id }} = 1;

                const addEditButton{{ $content->id }} = document.getElementById('addEditCastRoleButton{{ $content->id }}');
                addEditButton{{ $content->id }}.addEventListener('click', function (event) {
                    event.stopPropagation();
                    const currentRow = this.closest('.row');
                    const castSelect = currentRow.querySelector('select[name="edit_cast_id"]');
                    const roleSelect = currentRow.querySelector('select[name="edit_role_id"]');
                    const castId = castSelect.value;
                    const castName = castSelect.options[castSelect.selectedIndex].text;
                    const roleId = roleSelect.value;
                    const roleName = roleSelect.options[roleSelect.selectedIndex].text;

                    if (castId && roleId) {
                        editCastRolePairs{{ $content->id }}.push({ castId, roleId, castName, roleName });
                        updateEditCastRoleDisplay{{ $content->id }}();

                        const originalRow = document.querySelector('#editCastSection{{ $content->id }} .row');
                        const newRow = originalRow.cloneNode(true);
                        editRowCount{{ $content->id }}++;

                        newRow.querySelectorAll('select').forEach(element => {
                            if (element.name === 'edit_cast_id' || element.name === 'edit_role_id') {
                                element.name = `${element.name}_${editRowCount{{ $content->id }}}`;
                            }
                        });

                        newRow.querySelector('select[name="edit_cast_id_' + editRowCount{{ $content->id }} + '"]').selectedIndex = 0;
                        newRow.querySelector('select[name="edit_role_id_' + editRowCount{{ $content->id }} + '"]').selectedIndex = 0;

                        const buttonContainer = newRow.querySelector('.d-flex');
                        buttonContainer.innerHTML = `
                                            <div class="mb-3">
                                                <button type="button" id="removeEditCastButton_${editRowCount{{ $content->id }}}" class="btn btn-danger">Remove</button>
                                            </div>
                                        `;

                        originalRow.parentNode.appendChild(newRow);

                        newRow.querySelector('.btn-danger').addEventListener('click', function () {
                            newRow.remove();
                            editRowCount{{ $content->id }}--;
                            editCastRolePairs{{ $content->id }}.pop();
                            updateEditCastRoleDisplay{{ $content->id }}();
                            updateEditRemoveLastButtonVisibility{{ $content->id }}();
                        });

                        updateEditRemoveLastButtonVisibility{{ $content->id }}();

                        castSelect.selectedIndex = 0;
                        roleSelect.selectedIndex = 0;
                    } else {
                        alert('Please select both a cast member and a role.');
                    }
                });

                const removeEditButton{{ $content->id }} = document.getElementById('removeEditCastButton{{ $content->id }}');
                removeEditButton{{ $content->id }}.addEventListener('click', function () {
                    const rows = document.querySelectorAll('#editCastSection{{ $content->id }} .row');
                    if (rows.length > 1) {
                        rows[rows.length - 1].remove();
                        editRowCount{{ $content->id }}--;
                        editCastRolePairs{{ $content->id }}.pop();
                        updateEditCastRoleDisplay{{ $content->id }}();
                        updateEditRemoveLastButtonVisibility{{ $content->id }}();
                    }
                });

                function updateEditRemoveLastButtonVisibility{{ $content->id }}() {
                    removeEditButton{{ $content->id }}.style.display = editRowCount{{ $content->id }} > 1 ? 'inline-block' : 'none';
                }
                updateEditRemoveLastButtonVisibility{{ $content->id }}();

                function updateEditCastRoleDisplay{{ $content->id }}() {
                    let displayContainer = document.getElementById('edit-cast-list{{ $content->id }}');
                    displayContainer.innerHTML = '';
                    let hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'cast_role_pairs';
                    displayContainer.appendChild(hiddenInput);

                    if (editCastRolePairs{{ $content->id }}.length > 0) {
                        const ul = document.createElement('ul');
                        ul.className = 'list-group';
                        editCastRolePairs{{ $content->id }}.forEach((pair, index) => {
                            const li = document.createElement('li');
                            li.className = 'list-group-item d-flex justify-content-between align-items-center';
                            li.innerHTML = `
                                                ${pair.castName} as ${pair.roleName}
                                                <button type="button" class="btn btn-sm btn-danger remove-specific" data-index="${index}">Remove</button>
                                            `;
                            ul.appendChild(li);
                        });
                        displayContainer.appendChild(ul);
                        hiddenInput.value = JSON.stringify(editCastRolePairs{{ $content->id }});

                        document.querySelectorAll('#edit-cast-list{{ $content->id }} .remove-specific').forEach(button => {
                            button.addEventListener('click', function () {
                                const index = parseInt(this.getAttribute('data-index'));
                                editCastRolePairs{{ $content->id }}.splice(index, 1);
                                updateEditCastRoleDisplay{{ $content->id }}();
                                updateEditRemoveLastButtonVisibility{{ $content->id }}();
                            });
                        });
                    } else {
                        hiddenInput.value = '';
                    }
                }

                updateEditCastRoleDisplay{{ $content->id }}();

                const editContentForm{{ $content->id }} = document.getElementById('editContentForm{{ $content->id }}');
                editContentForm{{ $content->id }}.addEventListener('submit', function (e) {
                    console.log('Edit form submitted with cast_role_pairs:', document.querySelector('#edit-cast-list{{ $content->id }} input[name="cast_role_pairs"]').value);
                });
            @endforeach

                @if ($errors->any())
                    const addContentModal = new bootstrap.Modal(document.getElementById('addContentModal'));
                    addContentModal.show();
                @endif
                });
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.delete-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const contentId = this.getAttribute('data-id');

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

@endsection