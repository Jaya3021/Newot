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
            max-width: 1200px;
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
    @yield('content')
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('scripts')
</body>
</html>