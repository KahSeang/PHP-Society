<?php
require_once 'session_check.php';

$category_filter = isset($_GET['category']) ? $_GET['category'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_moment'])) {
    $connection = mysqli_connect('localhost', 'root', '', 'login');

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $image_path = ''; // Placeholder for image path
    $description = $_POST['description'];
    $category_id = $_POST['category'];
    $user_id = $_SESSION['user_id'];
    // Handle file upload
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_path = 'Websystemphp/moment/' . $image_name; 
        move_uploaded_file($image_tmp, $image_path);
    }

    $sql = "INSERT INTO moment (image_path, description, user_id, category_id) 
            VALUES ('$image_path', '$description', '$user_id', '$category_id')";
    if (mysqli_query($connection, $sql)) {
        echo "<script>alert('Moment added successfully');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($connection);
    }

    mysqli_close($connection);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_comment'])) {
    $connection = mysqli_connect('localhost', 'root', '', 'login');

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $moment_id = $_POST['moment_id'];
    $comment_text = $_POST['comment'];
    $user_id = $_SESSION['user_id']; 

    $sql = "INSERT INTO comments (moment_id, user_id, comment_text) 
            VALUES ('$moment_id', '$user_id', '$comment_text')";
    if (mysqli_query($connection, $sql)) {
        echo "<script>alert('Comment added successfully');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($connection);
    }

    // Close the database connection
    mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moments</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome library -->

    <style>
                 body {
    background-image: url('Websystemphp/10.webp');
    background-size: cover;
    background-position: center;
}   
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
    background-color: rgba(255, 255, 255, 0.8); /* White background with 80% opacity */
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        .container2 {
           width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8); /* White background with 80% opacity */
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .container2 {
            display: flex;
            flex-direction: column;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        
        label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="file"], textarea, select {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        input[type="file"] {
            cursor: pointer;
        }

        select {
            appearance: none;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .moments-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            grid-gap: 20px;
        }

        .moment {
            background-color: #fff;
            border-radius: 5px;
            padding: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease; /* Add animation */
        }

        .moment:hover {
            transform: translateY(-5px); /* Add hover effect */
        }

        .moment img {
            width: 100%;
            height: 200px;
            object-fit: cover; /* Maintain aspect ratio */
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .description {
            font-size: 14px;
            color: #666;
            margin: 0;
        }

        .user-info {
            font-size: 12px;
            color: #999;
            margin-top: 5px;
        }

        /* Additional styles for comments */
        .comment {
            margin-top: 10px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }

        .comment p {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .comment-form {
            margin-top: 10px;
        }

        .comment-form textarea {
            width: 100%;
            padding: 5px;
            margin-bottom: 5px;
        }

        .comment-form button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        .comment-form button:hover {
            background-color: #0056b3;
        }

        /* Additional styles for profile image */
        .comment img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 10px;
        }

        /* Styles for toggle button */
        .toggle-comments {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            margin-top: 10px;
        }
        
        
    </style>
</head>
<body>

<?php include 'header.php'; ?>
    <br><br>
<div class="container2">
    <h2>Add a New Moment</h2>
    <form action="moment.php" method="POST" enctype="multipart/form-data">
        <label for="image">Select Image:</label>
        <input type="file" name="image" id="image" accept="image/*" required><br>
        <label for="description">Description:</label><br>
        <textarea name="description" id="description" rows="4" cols="50" required></textarea><br>
        <label for="category">Choose Category:</label><br>
        <select name="category" id="category" required>
            <?php
            $connection = mysqli_connect('localhost', 'root', '', 'login');

            if (!$connection) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $sql = "SELECT * FROM category WHERE category_id != 1";
            $result = mysqli_query($connection, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<option value="' . $row['category_id'] . '">' . $row['category_name'] . '</option>';
                }
            } else {
                echo '<option value="">No categories found</option>';
            }

            // Close the database connection
            mysqli_close($connection);
            ?>
        </select><br>
        <input type="submit" name="submit_moment" value="Submit Moment">
    </form>
</div>

<div class="container">
    <h2>Moments</h2>
    <form action="moment.php" method="GET">
        <label for="category">Filter by Category:</label>
        <select name="category" id="category">
            <option value="">All Categories</option>
            <?php
            $connection = mysqli_connect('localhost', 'root', '', 'login');

            if ($connection) {
                $sql = "SELECT * FROM category WHERE category_id != 1";
                $result = mysqli_query($connection, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $selected = ($row['category_id'] == $category_filter) ? 'selected' : '';
                        echo '<option value="' . $row['category_id'] . '" ' . $selected . '>' . $row['category_name'] . '</option>';
                    }
                } else {
                    echo '<option value="">No categories found</option>';
                }

                mysqli_close($connection);
            } else {
                echo '<option value="">Failed to connect to the database</option>';
            }
            ?>
        </select>
        <button type="submit" style="background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 10px;
            font-size: 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-left: 20px;">Apply Filter</button>
    </form>
    <!-- Moments grid -->
    <div class="moments-grid">
        <?php
        $connection = mysqli_connect('localhost', 'root', '', 'login');

        if ($connection) {
            $sql = "SELECT moment.*, users.username, users.user_img, moment.uploaded_at 
                    FROM moment 
                    INNER JOIN users ON moment.user_id = users.user_id";

            if (!empty($category_filter)) {
                $sql .= " WHERE moment.category_id = " . mysqli_real_escape_string($connection, $category_filter);
            }

            $sql .= " ORDER BY moment.uploaded_at DESC";

            $result = mysqli_query($connection, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="moment">';
                    echo '<img src="' . $row['image_path'] . '" alt="Moment Image">';
                    echo '<p class="description">' . $row['description'] . '</p>';
                    echo '<div class="user-info">Uploaded by ' . $row['username'] . ' on ' . $row['uploaded_at'] . '</div>';

                    $moment_id = $row['moment_id'];
                    $comments_count_sql = "SELECT COUNT(*) AS count FROM comments WHERE moment_id = $moment_id";
                    $comments_count_result = mysqli_query($connection, $comments_count_sql);
                    $comments_count = mysqli_fetch_assoc($comments_count_result)['count'];
                    echo '<button class="toggle-comments" onclick="toggleComments(' . $moment_id . ')">Comments (' . $comments_count . ')</button>';

                    echo '<div id="comments-' . $moment_id . '" style="display: none;">';
                    $comments_sql = "SELECT comments.*, users.username, users.user_img 
                                     FROM comments 
                                     INNER JOIN users ON comments.user_id = users.user_id 
                                     WHERE moment_id = $moment_id";
                    $comments_result = mysqli_query($connection, $comments_sql);
                    if ($comments_result && mysqli_num_rows($comments_result) > 0) {
                        while ($comment_row = mysqli_fetch_assoc($comments_result)) {
                            echo '<div class="comment">';
                            if (!empty($comment_row['user_img'])) {
                                echo '<img src="' . $comment_row['user_img'] . '" alt="Profile Image">';
                            } else {
                                echo '<img src="Websystemphp/moment/profile.jpg" alt="Default Profile Image">';
                            }
                            echo '<p><strong>Username: ' . $comment_row['username'] . '</strong></p>';
                            echo '<p>' . $comment_row['comment_text'] . '</p>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>No comments yet.</p>';
                    }
                    echo '<div class="comment-form">';
                    echo '<form action="moment.php" method="POST">';
                    echo '<input type="hidden" name="moment_id" value="' . $moment_id . '">';
                    echo '<textarea name="comment" placeholder="Add a comment..." required></textarea><br>';
                    echo '<button type="submit" name="submit_comment">Post Comment</button>';
                    echo '</form>';
                    echo '</div>';
                    echo '</div>';

                    echo '</div>';
                }
            } else {
                echo '<p>No moments found.</p>';
            }

            mysqli_close($connection);
        } else {
            echo '<p>Failed to connect to the database.</p>';
        }
        ?>
    </div>
</div>

<script>
    function toggleComments(momentId) {
        var commentsDiv = document.getElementById('comments-' + momentId);
        if (commentsDiv.style.display === 'none') {
            commentsDiv.style.display = 'block';
        } else {
            commentsDiv.style.display = 'none';
        }
    }
</script>

</body>
<?php include('footer.php'); ?>

</html>