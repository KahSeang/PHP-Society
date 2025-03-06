<?php
session_start();

$username = isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : null;
?>
<?php include 'adminheader.php'; ?>
<?php include 'adminsidebar.php'; ?>

<style>
    
    
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .content {
        padding: 60px;
    }

    h2 {
        color: #333;
        text-align: center;
        margin-bottom: 20px;
    }

    .welcome-msg {
        font-size: 1.5em;
        text-align: center;
        margin-bottom: 20px;
    }

    .features {
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .features h3 {
        color: #333;
        margin-bottom: 10px;
    }

    .features ul {
        list-style-type: none;
        padding: 0;
    }

    .features ul li {
        margin-bottom: 10px;
    }

    .features ul li strong {
        color: #007bff;
    }
</style>

<div class="content">
    <div class="container-fluid">
        <h2>Welcome to the Admin Panel</h2>
        <?php echo "Welcome, $username!"; ?>
        
        <p>In this admin page, you can view and manage the following:</p>
        
        <ul>
            <li><strong>User:</strong> View user list, register new users, manage cookies, and access user home pages.</li>
            <li><strong>Admin:</strong> Register new admins and view admin list.</li>
            <li><strong>Event:</strong> Perform CRUD operations on events and categories, and adjust event categories.</li>
            <li><strong>Pages:</strong> Perform CRUD operations on the home page, about us page, and feedback page.</li>
            <li><strong>Employees:</strong> Manage employees.</li>
            <li><strong>Payment:</strong> Manage payment methods and payment details.</li>
            <li><strong>Report:</strong> View summary, profit, favorite, exception, and categories reports.</li>
        </ul>
    </div>
</div>

<?php include 'admin_footer.php'; ?>
    