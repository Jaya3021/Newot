<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTT Admin Panel - Compact UI</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap CSS (for modals) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts (Montserrat) -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #0d0d0d;
            color: #e5e5e5;
            font-family: 'Montserrat', sans-serif;
        }
        /* Sidebar Styling */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #1a1a1a 0%, #000 100%);
            padding-top: 20px;
            position: fixed;
            width: 200px;
            transition: all 0.3s ease;
        }
        .sidebar a {
            color: #e5e5e5;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: background 0.3s ease;
            font-size: 14px;
        }
        .sidebar a:hover {
            background-color: #e50914;
            color: #fff;
        }
        .sidebar i {
            margin-right: 8px;
        }
        /* Main Content */
        .main-content {
            margin-left: 200px;
            padding: 20px;
        }
        .table-container {
            background: linear-gradient(135deg, #1c1c1c 0%, #141414 100%);
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.5);
            max-width: 1200px; /* Slightly wider for more columns */
        }
        .table th, .table td {
            color: #e5e5e5;
            border-color: #333;
            padding: 6px 8px;
            font-size: 12px;
        }
        .table thead th {
            font-size: 13px;
        }
        .table tbody tr {
            transition: background 0.3s ease;
        }
        .table tbody tr:hover {
            background-color: #2a2a2a;
        }
        /* Modal Styling */
        .modal-content {
            background: linear-gradient(135deg, #1c1c1c 0%, #141414 100%);
            color: #e5e5e5;
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.5);
        }
        .modal-header {
            background: #000;
            border-bottom: 1px solid #333;
            padding: 10px;
        }
        .modal-footer {
            border-top: 1px solid #333;
            padding: 10px;
        }
        /* Form Styling */
        .form-group {
            position: relative;
            margin-bottom: 1rem;
        }
        .form-group label {
            position: absolute;
            top: 8px;
            left: 10px;
            color: #999;
            font-size: 12px;
            transition: all 0.2s ease;
            pointer-events: none;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            background-color: #333;
            color: #e5e5e5;
            border: 1px solid #555;
            border-radius: 6px;
            padding: 16px 10px 8px;
            width: 100%;
            font-size: 12px;
            transition: all 0.3s ease;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            background-color: #444;
            border-color: #e50914;
            outline: none;
            box-shadow: 0 0 6px rgba(229, 9, 20, 0.3);
        }
        .form-group input:focus + label,
        .form-group input:not(:placeholder-shown) + label,
        .form-group select:focus + label,
        .form-group select:not(:placeholder-shown) + label,
        .form-group textarea:focus + label,
        .form-group textarea:not(:placeholder-shown) + label {
            top: -6px;
            left: 8px;
            font-size: 10px;
            color: #e50914;
            background: #1c1c1c;
            padding: 0 4px;
        }
        .form-group textarea {
            resize: none;
        }
        /* Buttons */
        .btn-primary {
            background: linear-gradient(90deg, #e50914, #f40612);
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #f40612, #e50914);
            box-shadow: 0 2px 10px rgba(229, 9, 20, 0.5);
        }
        .btn-secondary {
            background: #555;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            transition: all 0.3s ease;
        }
        .btn-secondary:hover {
            background: #666;
        }
        /* Search Input */
        .search-container {
            position: relative;
            width: 20%;
        }
        .search-container input {
            background-color: #333;
            color: #e5e5e5;
            border: 1px solid #555;
            border-radius: 6px;
            padding: 6px 8px 6px 30px;
            width: 100%;
            font-size: 12px;
        }
        .search-container i {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            color: #999;
            font-size: 12px;
        }
        /* Pagination */
        .pagination .page-link {
            background-color: #333;
            color: #e5e5e5;
            border: 1px solid #555;
            border-radius: 6px;
            padding: 4px 8px;
            font-size: 12px;
        }
        .pagination .page-item.active .page-link {
            background: linear-gradient(90deg, #e50914, #f40612);
            border-color: #e50914;
        }
        /* Other Adjustments */
        h2 {
            font-size: 20px;
            margin-bottom: 10px;
        }
        .mb-8 {
            margin-bottom: 20px;
        }
        .flex.justify-between.mb-4 {
            margin-bottom: 10px;
        }
        p {
            font-size: 12px;
        }
    </style>
</head>
<body>
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
        <!-- Role Management Section -->
        <div class="mb-8">
            <h2>Role Management</h2>
            <div class="table-container">
                <div class="flex justify-between mb-4">
                    <div class="search-container">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search Roles">
                    </div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">Add New Role</button>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ROLE NAME</th>
                            <th>DESCRIPTION</th>
                            <th>STATUS</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Lead Actor</td>
                            <td>Main actor in the production</td>
                            <td>active</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#editRoleModal1"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Villain</td>
                            <td>Antagonist in the storyline</td>
                            <td>active</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#editRoleModal2"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="flex justify-between items-center">
                    <p>Showing 1 to 2 of 2 entries</p>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Cast Master Section -->
        <div class="mb-8">
            <h2>Cast Master</h2>
            <div class="table-container">
                <div class="flex justify-between mb-4">
                    <div class="search-container">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search Cast">
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
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Robert Downey Jr.</td>
                            <td><a href="#" class="text-red-600 hover:underline">/images/rdj.jpg</a></td>
                            <td>Known for Iron Man role in MCU.</td>
                            <td>active</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#editCastModal1"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Scarlett Johansson</td>
                            <td><a href="#" class="text-red-600 hover:underline">/images/scarlett.jpg</a></td>
                            <td>Famous for Black Widow in MCU.</td>
                            <td>active</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#editCastModal2"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="flex justify-between items-center">
                    <p>Showing 1 to 2 of 2 entries</p>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Genre Master Section -->
        <div class="mb-8">
            <h2>Genre Master</h2>
            <div class="table-container">
                <div class="flex justify-between mb-4">
                    <div class="search-container">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search Genres">
                    </div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGenreModal">Add New Genre</button>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>GENRE NAME</th>
                            <th>IMAGE</th>
                            <th>DESCRIPTION</th>
                            <th>STATUS</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Action</td>
                            <td><a href="#" class="text-red-600 hover:underline">/images/action.jpg</a></td>
                            <td>High-energy films with stunts and battles</td>
                            <td>active</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#editGenreModal1"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Thriller</td>
                            <td><a href="#" class="text-red-600 hover:underline">/images/thriller.jpg</a></td>
                            <td>Suspenseful narratives with unexpected twists</td>
                            <td>active</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#editGenreModal2"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="flex justify-between items-center">
                    <p>Showing 1 to 2 of 2 entries</p>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Content Master Section -->
        <div class="mb-8">
            <h2>Content Master</h2>
            <div class="table-container">
                <div class="flex justify-between mb-4">
                    <div class="search-container">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search Content">
                    </div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addContentModal">Add New Content</button>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>MOVIE NAME</th>
                            <th>GENRE</th>
                            <th>CAST</th>
                            <th>THUMBNAIL</th>
                            <th>TRAILER</th>
                            <th>RELEASE YEAR</th>
                            <th>CONTENT RATING</th>
                            <th>STATUS</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Avengers: Endgame</td>
                            <td>Action</td>
                            <td>Robert Downey Jr., Scarlett Johansson</td>
                            <td><a href="#" class="text-red-600 hover:underline">/thumbnails/endgame.jpg</a></td>
                            <td><a href="#" class="text-red-600 hover:underline">Watch Trailer</a></td>
                            <td>2019</td>
                            <td>PG-13</td>
                            <td>active</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#editContentModal1"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Black Widow</td>
                            <td>Thriller</td>
                            <td>Scarlett Johansson</td>
                            <td><a href="#" class="text-red-600 hover:underline">/thumbnails/blackwidow.jpg</a></td>
                            <td><a href="#" class="text-red-600 hover:underline">Watch Trailer</a></td>
                            <td>2021</td>
                            <td>PG-13</td>
                            <td>active</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#editContentModal2"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="flex justify-between items-center">
                    <p>Showing 1 to 2 of 2 entries</p>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Video Management Section -->
        <div>
            <h2>Video Management</h2>
            <div class="table-container">
                <div class="flex justify-between mb-4">
                    <div class="search-container">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search Videos">
                    </div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadVideoModal">Upload New Video</button>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>VIDEO TITLE</th>
                            <th>UPLOADED ON</th>
                            <th>LINK</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Avengers: Endgame</td>
                            <td>June 10, 2025</td>
                            <td><a href="#" class="text-red-600 hover:underline">Watch Now</a></td>
                            <td>
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Stranger Things S4</td>
                            <td>June 11, 2025</td>
                            <td><a href="#" class="text-red-600 hover:underline">Watch Now</a></td>
                            <td>
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="flex justify-between items-center">
                    <p>Showing 1 to 2 of 2 entries</p>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
                        </ul>
                    </nav>
                </div>
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
                    <form>
                        <div class="form-group">
                            <input type="text" id="roleName" placeholder=" ">
                            <label for="roleName">Role Name</label>
                        </div>
                        <div class="form-group">
                            <textarea id="roleDescription" placeholder=" " rows="3"></textarea>
                            <label for="roleDescription">Description</label>
                        </div>
                        <div class="form-group">
                            <select id="roleStatus" placeholder=" ">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <label for="roleStatus">Status</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Role Modal (Lead Actor) -->
    <div class="modal fade" id="editRoleModal1" tabindex="-1" aria-labelledby="editRoleModalLabel1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRoleModalLabel1">Edit Role</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <input type="text" id="roleName1" placeholder=" " value="Lead Actor">
                            <label for="roleName1">Role Name</label>
                        </div>
                        <div class="form-group">
                            <textarea id="roleDescription1" placeholder=" " rows="3">Main actor in the production</textarea>
                            <label for="roleDescription1">Description</label>
                        </div>
                        <div class="form-group">
                            <select id="roleStatus1" placeholder=" ">
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <label for="roleStatus1">Status</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Role Modal (Villain) -->
    <div class="modal fade" id="editRoleModal2" tabindex="-1" aria-labelledby="editRoleModalLabel2" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRoleModalLabel2">Edit Role</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <input type="text" id="roleName2" placeholder=" " value="Villain">
                            <label for="roleName2">Role Name</label>
                        </div>
                        <div class="form-group">
                            <textarea id="roleDescription2" placeholder=" " rows="3">Antagonist in the storyline</textarea>
                            <label for="roleDescription2">Description</label>
                        </div>
                        <div class="form-group">
                            <select id="roleStatus2" placeholder=" ">
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <label for="roleStatus2">Status</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Changes</button>
                </div>
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
                    <form>
                        <div class="form-group">
                            <input type="text" id="castName" placeholder=" ">
                            <label for="castName">Cast Name</label>
                        </div>
                        <div class="form-group">
                            <input type="text" id="castImage" placeholder=" ">
                            <label for="castImage">Image URL</label>
                        </div>
                        <div class="form-group">
                            <textarea id="description" placeholder=" " rows="3"></textarea>
                            <label for="description">Description</label>
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
                    <button type="button" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Cast Modal (Robert Downey Jr.) -->
    <div class="modal fade" id="editCastModal1" tabindex="-1" aria-labelledby="editCastModalLabel1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCastModalLabel1">Edit Cast</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <input type="text" id="castName1" placeholder=" " value="Robert Downey Jr.">
                            <label for="castName1">Cast Name</label>
                        </div>
                        <div class="form-group">
                            <input type="text" id="castImage1" placeholder=" " value="/images/rdj.jpg">
                            <label for="castImage1">Image URL</label>
                        </div>
                        <div class="form-group">
                            <textarea id="description1" placeholder=" " rows="3">Known for Iron Man role in MCU.</textarea>
                            <label for="description1">Description</label>
                        </div>
                        <div class="form-group">
                            <select id="castStatus1" placeholder=" ">
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <label for="castStatus1">Status</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Cast Modal (Scarlett Johansson) -->
    <div class="modal fade" id="editCastModal2" tabindex="-1" aria-labelledby="editCastModalLabel2" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCastModalLabel2">Edit Cast</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <input type="text" id="castName2" placeholder=" " value="Scarlett Johansson">
                            <label for="castName2">Cast Name</label>
                        </div>
                        <div class="form-group">
                            <input type="text" id="castImage2" placeholder=" " value="/images/scarlett.jpg">
                            <label for="castImage2">Image URL</label>
                        </div>
                        <div class="form-group">
                            <textarea id="description2" placeholder=" " rows="3">Famous for Black Widow in MCU.</textarea>
                            <label for="description2">Description</label>
                        </div>
                        <div class="form-group">
                            <select id="castStatus2" placeholder=" ">
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <label for="castStatus2">Status</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Changes</button>
                </div>
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
                    <form>
                        <div class="form-group">
                            <input type="text" id="genreName" placeholder=" ">
                            <label for="genreName">Genre Name</label>
                        </div>
                        <div class="form-group">
                            <input type="text" id="genreImage" placeholder=" ">
                            <label for="genreImage">Image URL</label>
                        </div>
                        <div class="form-group">
                            <textarea id="genreDescription" placeholder=" " rows="3"></textarea>
                            <label for="genreDescription">Description</label>
                        </div>
                        <div class="form-group">
                            <select id="genreStatus" placeholder=" ">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <label for="genreStatus">Status</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Genre Modal (Action) -->
    <div class="modal fade" id="editGenreModal1" tabindex="-1" aria-labelledby="editGenreModalLabel1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editGenreModalLabel1">Edit Genre</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <input type="text" id="genreName1" placeholder=" " value="Action">
                            <label for="genreName1">Genre Name</label>
                        </div>
                        <div class="form-group">
                            <input type="text" id="genreImage1" placeholder=" " value="/images/action.jpg">
                            <label for="genreImage1">Image URL</label>
                        </div>
                        <div class="form-group">
                            <textarea id="genreDescription1" placeholder=" " rows="3">High-energy films with stunts and battles</textarea>
                            <label for="genreDescription1">Description</label>
                        </div>
                        <div class="form-group">
                            <select id="genreStatus1" placeholder=" ">
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <label for="genreStatus1">Status</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Genre Modal (Thriller) -->
    <div class="modal fade" id="editGenreModal2" tabindex="-1" aria-labelledby="editGenreModalLabel2" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editGenreModalLabel2">Edit Genre</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <input type="text" id="genreName2" placeholder=" " value="Thriller">
                            <label for="genreName2">Genre Name</label>
                        </div>
                        <div class="form-group">
                            <input type="text" id="genreImage2" placeholder=" " value="/images/thriller.jpg">
                            <label for="genreImage2">Image URL</label>
                        </div>
                        <div class="form-group">
                            <textarea id="genreDescription2" placeholder=" " rows="3">Suspenseful narratives with unexpected twists</textarea>
                            <label for="genreDescription2">Description</label>
                        </div>
                        <div class="form-group">
                            <select id="genreStatus2" placeholder=" ">
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <label for="genreStatus2">Status</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Content Modal -->
    <div class="modal fade" id="addContentModal" tabindex="-1" aria-labelledby="addContentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addContentModalLabel">Add New Content</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <input type="text" id="movieName" placeholder=" ">
                            <label for="movieName">Movie Name</label>
                        </div>
                        <div class="form-group">
                            <select id="genreId" placeholder=" ">
                                <option value="1">Action</option>
                                <option value="2">Thriller</option>
                            </select>
                            <label for="genreId">Genre</label>
                        </div>
                        <div class="form-group">
                            <select id="castIds" multiple placeholder=" ">
                                <option value="1">Robert Downey Jr.</option>
                                <option value="2">Scarlett Johansson</option>
                            </select>
                            <label for="castIds">Cast (Hold Ctrl to select multiple)</label>
                        </div>
                        <div class="form-group">
                            <input type="text" id="thumbnail" placeholder=" ">
                            <label for="thumbnail">Thumbnail URL</label>
                        </div>
                        <div class="form-group">
                            <input type="text" id="trailerUrl" placeholder=" ">
                            <label for="trailerUrl">Trailer URL</label>
                        </div>
                        <div class="form-group">
                            <input type="number" id="releaseYear" placeholder=" " min="1900" max="2025">
                            <label for="releaseYear">Release Year</label>
                        </div>
                        <div class="form-group">
                            <textarea id="contentDescription" placeholder=" " rows="3"></textarea>
                            <label for="contentDescription">Description</label>
                        </div>
                        <div class="form-group">
                            <input type="text" id="language" placeholder=" ">
                            <label for="language">Language</label>
                        </div>
                        <div class="form-group">
                            <input type="number" id="duration" placeholder=" " min="1">
                            <label for="duration">Duration (minutes)</label>
                        </div>
                        <div class="form-group">
                            <input type="text" id="contentRating" placeholder=" ">
                            <label for="contentRating">Content Rating</label>
                        </div>
                        <div class="form-group">
                            <select id="contentStatus" placeholder=" ">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <label for="contentStatus">Status</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Content Modal (Avengers: Endgame) -->
    <div class="modal fade" id="editContentModal1" tabindex="-1" aria-labelledby="editContentModalLabel1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editContentModalLabel1">Edit Content</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <input type="text" id="movieName1" placeholder=" " value="Avengers: Endgame">
                            <label for="movieName1">Movie Name</label>
                        </div>
                        <div class="form-group">
                            <select id="genreId1" placeholder=" ">
                                <option value="1" selected>Action</option>
                                <option value="2">Thriller</option>
                            </select>
                            <label for="genreId1">Genre</label>
                        </div>
                        <div class="form-group">
                            <select id="castIds1" multiple placeholder=" ">
                                <option value="1" selected>Robert Downey Jr.</option>
                                <option value="2" selected>Scarlett Johansson</option>
                            </select>
                            <label for="castIds1">Cast (Hold Ctrl to select multiple)</label>
                        </div>
                        <div class="form-group">
                            <input type="text" id="thumbnail1" placeholder=" " value="/thumbnails/endgame.jpg">
                            <label for="thumbnail1">Thumbnail URL</label>
                        </div>
                        <div class="form-group">
                            <input type="text" id="trailerUrl1" placeholder=" " value="https://youtube.com/watch?v=endgame">
                            <label for="trailerUrl1">Trailer URL</label>
                        </div>
                        <div class="form-group">
                            <input type="number" id="releaseYear1" placeholder=" " value="2019" min="1900" max="2025">
                            <label for="releaseYear1">Release Year</label>
                        </div>
                        <div class="form-group">
                            <textarea id="contentDescription1" placeholder=" " rows="3">The epic conclusion to the Infinity Saga.</textarea>
                            <label for="contentDescription1">Description</label>
                        </div>
                        <div class="form-group">
                            <input type="text" id="language1" placeholder=" " value="English">
                            <label for="language1">Language</label>
                        </div>
                        <div class="form-group">
                            <input type="number" id="duration1" placeholder=" " value="181" min="1">
                            <label for="duration1">Duration (minutes)</label>
                        </div>
                        <div class="form-group">
                            <input type="text" id="contentRating1" placeholder=" " value="PG-13">
                            <label for="contentRating1">Content Rating</label>
                        </div>
                        <div class="form-group">
                            <select id="contentStatus1" placeholder=" ">
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <label for="contentStatus1">Status</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Content Modal (Black Widow) -->
    <div class="modal fade" id="editContentModal2" tabindex="-1" aria-labelledby="editContentModalLabel2" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editContentModalLabel2">Edit Content</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <input type="text" id="movieName2" placeholder=" " value="Black Widow">
                            <label for="movieName2">Movie Name</label>
                        </div>
                        <div class="form-group">
                            <select id="genreId2" placeholder=" ">
                                <option value="1">Action</option>
                                <option value="2" selected>Thriller</option>
                            </select>
                            <label for="genreId2">Genre</label>
                        </div>
                        <div class="form-group">
                            <select id="castIds2" multiple placeholder=" ">
                                <option value="1">Robert Downey Jr.</option>
                                <option value="2" selected>Scarlett Johansson</option>
                            </select>
                            <label for="castIds2">Cast (Hold Ctrl to select multiple)</label>
                        </div>
                        <div class="form-group">
                            <input type="text" id="thumbnail2" placeholder=" " value="/thumbnails/blackwidow.jpg">
                            <label for="thumbnail2">Thumbnail URL</label>
                        </div>
                        <div class="form-group">
                            <input type="text" id="trailerUrl2" placeholder=" " value="https://youtube.com/watch?v=blackwidow">
                            <label for="trailerUrl2">Trailer URL</label>
                        </div>
                        <div class="form-group">
                            <input type="number" id="releaseYear2" placeholder=" " value="2021" min="1900" max="2025">
                            <label for="releaseYear2">Release Year</label>
                        </div>
                        <div class="form-group">
                            <textarea id="contentDescription2" placeholder=" " rows="3">A thrilling tale of Natasha Romanoff's past.</textarea>
                            <label for="contentDescription2">Description</label>
                        </div>
                        <div class="form-group">
                            <input type="text" id="language2" placeholder=" " value="English">
                            <label for="language2">Language</label>
                        </div>
                        <div class="form-group">
                            <input type="number" id="duration2" placeholder=" " value="134" min="1">
                            <label for="duration2">Duration (minutes)</label>
                        </div>
                        <div class="form-group">
                            <input type="text" id="contentRating2" placeholder=" " value="PG-13">
                            <label for="contentRating2">Content Rating</label>
                        </div>
                        <div class="form-group">
                            <select id="contentStatus2" placeholder=" ">
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <label for="contentStatus2">Status</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Video Modal -->
    <div class="modal fade" id="uploadVideoModal" tabindex="-1" aria-labelledby="uploadVideoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadVideoModalLabel">Upload Video</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <input type="text" id="videoTitle" placeholder=" ">
                            <label for="videoTitle">Video Title</label>
                        </div>
                        <div class="form-group">
                            <input type="file" id="videoFile" placeholder=" " accept="video/*">
                            <label for="videoFile">Upload Video</label>
                        </div>
                        <div class="form-group">
                            <input type="text" id="videoLink" placeholder=" ">
                            <label for="videoLink">Video Link (Optional)</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Upload</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>