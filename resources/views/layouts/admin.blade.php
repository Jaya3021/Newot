<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTT Admin Panel - @yield('title')</title>

    {{-- REMOVE TAILWIND CSS TO AVOID CONFLICTS --}}
    {{-- <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"> --}}

    {{-- Font Awesome for icons (Good to keep) --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    {{-- Bootstrap CSS (Keep and ensure it's the right version) --}}
    {{-- You had 5.3.2, I'll update to latest 5.3.3 for good measure, but 5.3.2 is fine if you prefer --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    {{-- Google Fonts (Keep) --}}
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">

    {{-- Your Custom CSS (IMPORTANT: Review and potentially adjust based on Bootstrap) --}}
    <style>
        body {
            background-color: #0d0d0d;
            color: #e5e5e5;
            font-family: 'Montserrat', sans-serif;
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #1a1a1a 0%, #000 100%);
            padding-top: 20px;
            position: fixed; /* Fixed sidebar */
            width: 200px;
            transition: all 0.3s ease;
            z-index: 1030; /* Ensure sidebar is above other content but below modals if needed */
        }
        .sidebar a {
            color: #e5e5e5;
            padding: 10px 20px;
            display: block;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .sidebar a:hover {
            background: linear-gradient(to right, #f40612, #e50914);
            color: #fff;
        }
        .main-content {
            margin-left: 200px; /* Offset for the fixed sidebar */
            padding: 20px;
            /* Consider adding responsive padding for smaller screens if needed */
        }
        .table-container {
            background: linear-gradient(to right, #1c2526, #2e2b2f);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            overflow-x: auto; /* IMPORTANT: Add this for responsive tables */
        }
        /* Bootstrap's .table class already provides basic styling,
           but your custom table styles are conflicting or incomplete for it.
           Let's adjust them to work with Bootstrap. */
        table.table { /* Target only tables with .table class */
            /* Bootstrap tables already have width: 100%; and border-collapse */
            color: #e5e5e5; /* Set text color for the table content */
            background-color: transparent; /* Ensure background is transparent if you want the container gradient to show */
        }
        table.table th, table.table td {
            padding: 8px;
            text-align: center;
            font-size: 12px;
            border-top: 1px solid #444; /* Add a border to cells for separation */
        }
        table.table th {
            background-color: #333;
            color: #e5e5e5;
            border-bottom: 2px solid #555; /* Stronger border for header */
        }
        table.table tbody tr:hover {
            background-color: #444; /* Darker hover for dark theme */
        }
        table.table thead th {
             vertical-align: bottom; /* Align header text to bottom if multiple lines */
             border-bottom: 2px solid #dee2e6; /* Bootstrap default border color */
        }


        /* Search container and input styles */
        .search-container {
            position: relative;
            /* Adjust width as needed, mb-6 is on the form not this div */
            /* Tailwind flex classes like flex, justify-between should be applied to the parent form or div */
        }
        .search-container i {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            color: #666;
            z-index: 2; /* Ensure icon is above input */
        }
        .search-container input {
            width: 100%;
            padding: 8px 30px 8px 30px; /* Adjust padding for icon */
            background: #333;
            border: none; /* Remove border if you want a cleaner look */
            border-radius: 4px;
            color: #e5e5e5;
        }
        .search-container input:focus {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(229, 9, 20, 0.25); /* Focus effect */
        }

        /* Form Group (Floating Label) Styles - Ensure these are robust */
        .form-group {
            position: relative;
            margin-bottom: 20px;
        }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 8px 10px; /* Adjusted padding */
            background: #333;
            border: none;
            border-bottom: 2px solid #666;
            color: #e5e5e5;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus {
            border-bottom: 2px solid #e50914;
            outline: none;
        }
        .form-group label {
            position: absolute;
            top: 8px;
            left: 10px;
            color: #666;
            font-size: 14px;
            transition: all 0.3s ease;
            pointer-events: none; /* Prevent label from interfering with input click */
        }
        /* Floating label effect */
        .form-group input:focus ~ label,
        .form-group input:not(:placeholder-shown) ~ label,
        .form-group textarea:focus ~ label,
        .form-group textarea:not(:placeholder-shown) ~ label,
        .form-group select:focus ~ label,
        .form-group select:not(:placeholder-shown) ~ label {
            top: -15px; /* Adjusted to be higher */
            font-size: 10px; /* Smaller font size */
            color: #e50914;
        }

        /* Modal Styles */
        .modal-content {
            background: linear-gradient(to right, #1c2526, #2e2b2f);
            color: #e5e5e5;
        }
        .modal-header {
            background: #333;
            color: #e5e5e5;
            border-bottom: none;
        }
        .modal-footer button.btn-primary {
            background: linear-gradient(to right, #f40612, #e50914);
            border: none;
        }
        /* Button Styles (Ensure consistency with Bootstrap) */
        .btn-primary {
            background: linear-gradient(to right, #f40612, #e50914);
            border: none; /* Removed #none, added 'none' */
            color: #e5e5e5; /* Corrected typo: e5e5e5e5 to e5e5e5 */
            padding: 8px 15px; /* Added padding for better button look */
            border-radius: 4px; /* Added border-radius */
        }
        .btn-primary:hover {
            background: linear-gradient(to right, #e50914, #f40612);
        }
        .btn-secondary {
            background: #444;
            border: none;
            color: #e5e5e5;
            padding: 8px 15px;
            border-radius: 4px;
        }
        .btn-secondary:hover {
            background: #555;
        }
        /* Outline buttons for actions */
        .btn-outline-primary {
            color: #e50914;
            border-color: #e50914;
        }
        .btn-outline-primary:hover {
            background-color: #e50914;
            color: #fff;
        }
        .btn-outline-danger {
            color: #dc3545; /* Bootstrap red */
            border-color: #dc3545;
        }
        .btn-outline-danger:hover {
            background-color: #dc3545;
            color: #fff;
        }

        /* Alert Success (color was wrong: #4 is not a color) */
        .alert-success {
            background-color: #1a1a1a;
            color: #28a745; /* Green color for success text */
            border: 1px solid #28a745; /* Add a subtle border */
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        /* Pagination Styling (Adjust for your dark theme) */
        .pagination {
            display: flex;
            padding-left: 0;
            list-style: none;
            border-radius: 0.25rem;
        }
        .page-item .page-link {
            position: relative;
            display: block;
            padding: 0.5rem 0.75rem;
            margin-left: -1px;
            line-height: 1.25;
            color: #e5e5e5; /* Page link text color */
            background-color: #333; /* Page link background */
            border: 1px solid #444; /* Page link border */
            text-decoration: none;
        }
        .page-item.active .page-link {
            z-index: 3;
            color: #fff;
            background-color: #e50914; /* Active page background */
            border-color: #e50914; /* Active page border */
        }
        .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            background-color: #222;
            border-color: #444;
        }
        .page-item .page-link:hover {
            z-index: 2;
            color: #fff;
            background-color: #e50914;
            border-color: #e50914;
        }
    </style>
    {{-- Laravel Vite (Keep this as it includes your app.js for interactivity) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="d-flex"> {{-- Use Bootstrap's flexbox for layout --}}
        <div class="sidebar">
            <h3 class="text-center mb-4 text-danger font-weight-bold">OTT Admin</h3> {{-- Use Bootstrap/custom classes for color/weight --}}
            <a href="{{ route('dashboard') }}" class="d-block py-2 px-3 text-white text-decoration-none"><i class="fas fa-home me-2"></i> Dashboard</a>
            <a href="{{ route('roles.index') }}" class="d-block py-2 px-3 text-white text-decoration-none"><i class="fas fa-user-tag me-2"></i> Role Management</a>
            <a href="{{ route('casts.index') }}" class="d-block py-2 px-3 text-white text-decoration-none"><i class="fas fa-film me-2"></i> Cast Master</a>
            <a href="{{ route('genres.index') }}" class="d-block py-2 px-3 text-white text-decoration-none"><i class="fas fa-theater-masks me-2"></i> Genre Master</a>
            <a href="{{ route('contents.index') }}" class="d-block py-2 px-3 text-white text-decoration-none"><i class="fas fa-play-circle me-2"></i> Content Master</a>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="d-block py-2 px-3 text-white text-decoration-none"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
        <div class="main-content flex-grow-1"> {{-- Let main-content take remaining space --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            @yield('content')
        </div>
    </div> {{-- Close d-flex --}}

    {{-- Bootstrap JS (Bundle includes Popper) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    {{-- Laravel Vite (Keep this as it includes your app.js for interactivity) --}}
    {{-- This will load resources/js/app.js which should contain your Bootstrap JS if you're not using the CDN --}}
    {{-- If you're using CDN for Bootstrap JS, you might not need app.js for Bootstrap, but it's good for custom JS --}}
    {{-- If app.js is just for Bootstrap, remove the CDN and rely on Vite --}}
    {{-- If app.js contains your own custom JS, keep both (Vite and CDN) --}}
    {{-- @vite('resources/js/app.js') --}}

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/content-management.js') }}"></script>
    @stack('scripts')
</body>
</html>