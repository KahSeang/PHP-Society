<style>
/* Style for the dropdown menu */
/* Global styles */
body {
    font-family: unset;
    margin: 0;
    padding: 0;
}

/* Sidebar styles */
.sidebar {
    background-color: #343a40;
    color: #fff;
    height: 100vh;
    width: 250px;
    position: fixed;
    top: 0;
    left: 0;
    overflow-y: auto;
    z-index: 1;
}

/* Navigation styles */
.nav {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.nav-item {
    margin-bottom: 5px;
    width: 100%;
    transition: all 0.3s ease; /* Add transition for smoother animation */
}
.nav-link {
    display: block;
    padding: 10px;
    color: #fff;
    text-decoration: none;
    transition: background-color 0.3s;
    
}

.nav-link:hover {
    background-color: #495057;
}

/* Dropdown menu styles */
.sub-menu {
    display: none;
    background-color: #495057;
    padding-left: 20px;
    border-radius: 0 0 5px 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    transition: display 0.5s ease; /* Adjust the transition duration as needed */
}

.sub-menu li {
    margin-bottom: 5px;
    text-decoration: none;
    padding: 5px;
    transition: background-color 0.3s, color 0.3s;
    list-style-type:none;/* Add transitions for color and background */
    width:100%;
}

.sub-menu li:hover{
    background-color: #888;
}

.sub-menu li a {
    color: #ffffff;
    text-decoration: none;
}

.nav-item:hover .sub-menu {
    display: block;
}


</style>

<div class="sidebar">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="admin.php">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-users"></i> User
            </a>
            <ul class="sub-menu">
                <li><a href="watchuser.php">User List</a></li>
                <li><a href="register.php">User Register</a></li>
                <li><a href="AdminCookies.php">Cookies</a></li>
                <li><a href="home.php">User Home Page</a></li>
                 <li><a href="adminfeedback.php">User Feedback </a></li>

            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-users"></i> Admin
            </a>
            <ul class="sub-menu">
                <li><a href="registerAdmin.php">Admin Register</a></li>
                <li><a href="watchadmin.php">Admin List</a></li>
            </ul>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-users"></i> Event
            </a>
            <ul class="sub-menu">
                <li><a href="editevent.php">CRUD Event</a></li>
                <li><a href="addcategory.php">CRUD Categories</a></li>
                <li><a href="adminCategory2.php">Adjustment Event Categories</a></li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-users"></i> Pages Edit
            </a>
            <ul class="sub-menu">
                <li><a href="adminedithome.php">CRUD Home Page</a></li>
                <li><a href="AdminAboutus.php">CRUD About Us</a></li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="employee.php">
                <i class="fas fa-users"></i> Employees
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-users"></i> Payment
            </a>
            <ul class="sub-menu">
                <li><a href="adminPaymethod.php">Payment Method</a></li>
                <li><a href="adminPayment.php">Payment Details</a></li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-users"></i> Report
            </a>
            <ul class="sub-menu">
                <li><a href="AdminSumary.php">Summary Report</a></li>
                <li><a href="AdminProfit.php">Profit Report</a></li>
                <li><a href="AdminFavourite.php">Favourite Report</a></li>
                <li><a href="AdminExp.php">Exception Report</a></li>
                <li><a href="adminCategoryR.php">Categories Report</a></li>
            </ul>
        </li>
    </ul>
</div>