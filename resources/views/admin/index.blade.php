@extends('layouts.app')

@section('content')
<!-- Sidebar -->
<div class="sidebar">
    <h3 class="text-center mb-4 text-red-600 text-lg font-bold">OTT Admin</h3>
    <a href="#dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="#roles"><i class="fas fa-user-tag"></i> Role Management</a>
    <a href="#cast"><i class="fas fa-film"></i> Cast Master</a>
    <a href="#genres"><i class="fas fa-theater-masks"></i> Genre Master</a>
    <a href="#content"><i class="fas fa-play-circle"></i> Content Master</a>
    <a href="#videos"><i class="fas fa-video"></i> Video Management</a>
    <a href="#analytics"><i class="fas fa-chart-bar"></i> Analytics</a>
    <a href="#settings"><i class="fas fa-cog"></i> Settings</a>
</div>

<!-- Main Content -->
<div class="main-content">
    <!-- Cast Master Section -->
    <div class="mb-8">
        <h2>Cast Master</h2>
        <div class="table-container">
            <div class="flex justify-between mb-4">
                <div class="search-container">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchCast" placeholder="Search Cast">
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCastModal">Add New Cast</button>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>CAST NAME</th>
                        <th>IMAGE</th>
                        <th>DESCRIPTION</th>
                        <th>STATUS</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody id="castTableBody"></tbody>
            </table>
            <div class="flex justify-between items-center">
                <p id="castTableInfo">Showing 0 to 0 of 0 entries</p>
                <nav>
                    <ul class="pagination">
                        <li class="page-item"><a class="page-link" href="#" id="castPrev">Previous</a></li>
                        <li class="page-item"><a class="page-link" href="#" id="castNext">Next</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <!-- Add other sections (Genre Master, Content Master, etc.) similarly -->
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
                <form id="addCastForm">
                    <div class="form-group">
                        <input type="text" id="castName" placeholder=" ">
                        <label for="castName">Cast Name</label>
                    </div>
                    <div class="form-group">
                        <input type="text" id="castImage" placeholder=" ">
                        <label for="castImage">Image URL</label>
                    </div>
                    <div class="form-group">
                        <textarea id="castDescription" placeholder=" " rows="3"></textarea>
                        <label for="castDescription">Description</label>
                    </div>
                    <div class="form-group">
                        <select id="castStatus" placeholder=" ">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <label for="castStatus">Status</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="addCast()">Submit</button>
            </div>
        </div>
    </div>
</div>
<!-- Add other modals similarly -->
@endsection

@section('scripts')
<script>
    let currentCastPage = 1;
    const castsPerPage = 10;

    $(document).ready(function() {
        loadCasts();
        $('#searchCast').on('input', loadCasts);
    });

    function loadCasts() {
        const search = $('#searchCast').val();
        $.ajax({
            url: '/api/cast',
            method: 'GET',
            success: function(data) {
                const filteredData = data.filter(cast => cast.cast_name.toLowerCase().includes(search.toLowerCase()));
                const start = (currentCastPage - 1) * castsPerPage;
                const end = start + castsPerPage;
                const paginatedData = filteredData.slice(start, end);

                $('#castTableBody').empty();
                paginatedData.forEach(cast => {
                    $('#castTableBody').append(`
                        <tr>
                            <td>${cast.id}</td>
                            <td>${cast.cast_name}</td>
                            <td><a href="#" class="text-red-600 hover:underline">${cast.image || 'N/A'}</a></td>
                            <td>${cast.description || 'N/A'}</td>
                            <td>${cast.status}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-2" onclick="editCast(${cast.id})"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteCast(${cast.id})"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    `);
                });

                $('#castTableInfo').text(`Showing ${start + 1} to ${Math.min(end, filteredData.length)} of ${filteredData.length} entries`);
                $('#castPrev').parent().toggleClass('disabled', currentCastPage === 1);
                $('#castNext').parent().toggleClass('disabled', end >= filteredData.length);
            }
        });
    }

    function addCast() {
        const cast = {
            cast_name: $('#castName').val(),
            image: $('#castImage').val(),
            description: $('#castDescription').val(),
            status: $('#castStatus').val(),
        };
        $.ajax({
            url: '/api/cast',
            method: 'POST',
            data: cast,
            success: function() {
                $('#addCastModal').modal('hide');
                loadCasts();
            }
        });
    }

    function editCast(id) {
        // Implement edit functionality with a modal
        alert('Edit functionality for cast ID ' + id + ' to be implemented');
    }

    function deleteCast(id) {
        if (confirm('Are you sure you want to delete this cast?')) {
            $.ajax({
                url: `/api/cast/${id}`,
                method: 'DELETE',
                success: function() {
                    loadCasts();
                }
            });
        }
    }

    $('#castPrev').click(function(e) {
        e.preventDefault();
        if (currentCastPage > 1) {
            currentCastPage--;
            loadCasts();
        }
    });

    $('#castNext').click(function(e) {
        e.preventDefault();
        currentCastPage++;
        loadCasts();
    });
</script>
@endsection