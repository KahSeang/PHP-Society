<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<style>
    /* Custom styling */
    .sidebar {
        height: 100%;
        width: 250px;
        position: fixed;
        top: 0;
        left: 0;
        background-color: #333;
        padding-top: 20px;
        transition: all 0.3s;
    }
    .sidebar a {
        padding: 10px 15px;
        text-decoration: none;
        font-size: 18px;
        color: white;
        display: flex;
        align-items: center;
    }
    .sidebar a:hover {
        background-color: #555;
    }
    .sidebar .submenu {
        display: none;
        padding-left: 20px;
    }
    .sidebar.active .submenu {
        display: block;
    }
    .arrow {
        margin-left: auto;
        font-size: 12px;
    }
</style>
</head>
<body>

<div class="sidebar">
    <a href="#home">Home <span class="arrow">&#9658;</span></a>
    <div class="submenu">
        <a href="#home/submenu1">Submenu 1</a>
        <a href="#home/submenu2">Submenu 2</a>
        <!-- Add more submenu items as needed -->
    </div>
    <a href="#dashboard">Dashboard</a>
    <a href="#posts">Posts</a>
    <a href="#users">Users</a>
    <!-- Add more links as needed -->
</div>

<div class="content">
    <!-- Main content area -->
    <h1>Welcome to the Admin Dashboard</h1>
    <p>This is where your main content will go.</p>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function(){
        $('.sidebar a[href="#home"]').on('click', function(){
            $('.sidebar').toggleClass('active');
        });
    });
</script>

</body>
</html>
    