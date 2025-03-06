<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title></title>
    <style>
        #navbar {
            background-color: #333;
            overflow: hidden;
        }
        
        #navbar a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }
        
        #navbar a:hover {
            background-color: #ddd;
            color: black;
        }
    </style>
</head>
<body>
    <header>   
        <div id="navbar">
            <div id="links">
               
                <?php
                $connection = mysqli_connect('localhost', 'root', '', 'login');
                
                if ($connection) {
                    $sql = "SELECT category_id, category_name FROM category";
                    $result = mysqli_query($connection, $sql);
                    
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<a href="?category_id=' . $row['category_id'] . '">' . $row['category_name'] . '</a>';
                        }
                    } else {
                        echo '<a href="#">No categories found</a>';
                    }
                    
                    mysqli_close($connection);
                } else {
                    echo '<a href="#">Failed to connect to the database</a>';
                }
                ?>
            </div>
        </div>
    </header>
</body>
</html>
