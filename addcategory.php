    <?php
    include 'adminheader.php';
    include 'adminsidebar.php';

    // Handle category deletion
    if(isset($_POST['delete_category'])) {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "login";

        // Create connection
        $connection = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }

        $category_id = $_POST['category_id'];

        // Prepare and execute the SQL query to delete the category from the database
        $sql = "DELETE FROM category WHERE category_id = ?";
        $statement = $connection->prepare($sql);
        $statement->bind_param("i", $category_id);

        if ($statement->execute()) {
            // Category deleted successfully
            echo "<script>alert('Category deleted successfully');</script>";
        } else {
            // Error occurred while deleting category
            echo "<script>alert('Error: " . $statement->error . "');</script>";
        }

        // Close the database connection
        $statement->close();
        $connection->close();
    }

    // Handle category update
    if(isset($_POST['update_category'])) {
        // Database connection parameters
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "login";

        // Create connection
        $connection = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }

        $category_id = $_POST['category_id'];
        $new_category_name = $_POST['new_category_name'];

        if ($_FILES['new_category_image']['error'] === UPLOAD_ERR_OK) {
            $imageTmpName = $_FILES['new_category_image']['tmp_name'];
            $uploadDir = 'uploads/';
            $newFileName = uniqid() . '_' . basename($_FILES['new_category_image']['name']);
            $new_category_image = $uploadDir . $newFileName;
            move_uploaded_file($imageTmpName, $new_category_image);
        }

        $sql = "UPDATE category SET category_name = ?, category_image = ? WHERE category_id = ?";
        $statement = $connection->prepare($sql);
        $statement->bind_param("ssi", $new_category_name, $new_category_image, $category_id);

        if ($statement->execute()) {
            echo "<script>alert('Category updated successfully');</script>";
        } else {
            // Error occurred while updating category
            echo "<script>alert('Error: " . $statement->error . "');</script>";
        }

        // Close the database connection
        $statement->close();
        $connection->close();
    }

    if(isset($_POST['create_category'])) {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "login";

        $connection = new mysqli($servername, $username, $password, $dbname);

        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }

        $category_name = $_POST['category_name'];

        if ($_FILES['category_image']['error'] === UPLOAD_ERR_OK) {
            $imageTmpName = $_FILES['category_image']['tmp_name'];
            $uploadDir = 'uploads/';
            $newFileName = uniqid() . '_' . basename($_FILES['category_image']['name']);
            $category_image = $uploadDir . $newFileName;
            move_uploaded_file($imageTmpName, $category_image);
        }

        $sql = "INSERT INTO category (category_name, category_image) VALUES (?, ?)";
        $statement = $connection->prepare($sql);
        $statement->bind_param("ss", $category_name, $category_image);

        if ($statement->execute()) {
            echo "<script>alert('Category created successfully');</script>";
        } else {
            echo "<script>alert('Error: " . $statement->error . "');</script>";
        }

        // Close the database connection
        $statement->close();
        $connection->close();
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <title>Category CRUD</title>
        <style>
            /* Add CSS styles for sidebar */
            #sidebar {
                width: 250px;
                position: fixed;
                top: 0;
                left: 0;
                height: 100%;
                background-color: #333;
                padding-top: 50px; /* Adjust this value according to your header height */
            }

            #content {
                margin-left: 250px; /* Ensure content doesn't overlap with sidebar */
                padding: 20px;
                margin-top: 80px;
            }
        </style>
    </head>
    <body>
        <!-- Sidebar -->
        <?php include 'adminsidebar.php'; ?>

        <!-- Content -->
        <div id="content">
            <h2>Category CRUD Operations</h2>

            <!-- Form for creating a new category -->
            <h3>Create Category</h3>
            <form action="" method="POST" enctype="multipart/form-data">
                <label for="category_name">Category Name:</label>
                <input type="text" id="category_name" name="category_name" required>
                <label for="category_image">Image:</label>
                <input type="file" id="category_image" name="category_image" accept="image/*" required>
                <button type="submit" name="create_category">Create</button>
            </form>

            <!-- Form for updating an existing category -->
            <h3>Update Category</h3>
            <form action="" method="POST" enctype="multipart/form-data">
                <label for="category_id">Category ID:</label>
                <select id="category_id" name="category_id" required>
                    <?php
    // Database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "login";

    $connection = new mysqli($servername, $username, $password, $dbname);

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    $sql = "SELECT category_id, category_name FROM category";
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row['category_id'] != 1) {
                echo "<option value='" . $row['category_id']    . "'>" .$row['category_id'] . $row['category_name'] . "</option>";
            }
        }
    }
    ?>
                    
                    
                </select>
                <label for="new_category_name">New Category Name:</label>
                <input type="text" id="new_category_name" name="new_category_name" required>
                <label for="new_category_image">New Image:</label>
                <input type="file" id="new_category_image" name="new_category_image" accept="image/*">
                <button type="submit" name="update_category">Update</button>
            </form>

            <!-- Display categories -->
            <h3>View Categories</h3>
            <?php
            // Include your database connection file here
            // Example: include 'db_connection.php';

            // Database connection parameters
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "login";

            // Create connection
            $connection = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($connection->connect_error) {
                die("Connection failed: " . $connection->connect_error);
            }

            // Assuming you have established a database connection and stored it in $connection
            $query = "SELECT * FROM category";
            $result = mysqli_query($connection, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                echo "<table class='table'>";
                echo "<thead class='thead-dark'><tr><th>ID</th><th>Name</th><th>Image</th><th>Action</th></tr></thead>";
                echo "<tbody>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['category_id']}</td>";
                    echo "<td>{$row['category_name']}</td>";
                    // Assuming you have a column named 'category_image' in your categories table
                    echo "<td><img src='{$row['category_image']}' alt='Category Image' style='width: 100px; height: 100px;'></td>";
                    echo "<td>
                            <form action='' method='POST'>
                                <input type='hidden' name='category_id' value='{$row['category_id']}'>
                                <button type='submit' name='delete_category'>Delete</button>
                            </form>
                          </td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "No categories found.";
            }
            ?>
        </div>

        <?php include 'admin_footer.php'; ?>
    </body>
    </html>
