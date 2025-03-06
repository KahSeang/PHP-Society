    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Event Listing</title>
        <link rel="stylesheet" href="styles.css"> <!-- Include your CSS file -->
    </head>
    <body>
        <?php include 'adminheader.php'; ?>
        <?php include 'adminsidebar.php'; ?>
        <div class="content">
            <?php
            require_once 'db_connection.php';

            // Handle form submission to move event to another category
            if(isset($_POST['move_event']) && isset($_POST['event_id']) && isset($_POST['new_category'])) {
                $event_id = $_POST['event_id'];
                $new_category_id = $_POST['new_category'];

                // Update the category of the event in the database
                $update_query = "UPDATE event SET category_id = $new_category_id WHERE event_id = $event_id";
                mysqli_query($conn, $update_query);
            }

            $query_categories = "SELECT * FROM category WHERE category_id <> 1"; // Exclude category_id 1
            $result_categories = mysqli_query($conn, $query_categories);

            if (mysqli_num_rows($result_categories) > 0) {
                while ($row_category = mysqli_fetch_assoc($result_categories)) {
                    $category_id = $row_category['category_id'];
                    $category_name = $row_category['category_name'];

                    echo "<h2>$category_name</h2>";

                    // Retrieve events for the current category
                    $query_events = "SELECT * FROM event WHERE category_id = $category_id";
                    $result_events = mysqli_query($conn, $query_events);

                    echo "<ul>";

                    // Check if events exist for the current category
                    if (mysqli_num_rows($result_events) > 0) {
                        // Loop through each event
                        while ($row_event = mysqli_fetch_assoc($result_events)) {
                            $event_id = $row_event['event_id'];
                            $event_name = $row_event['event_name'];
                            $event_location = $row_event['event_location'];
                            $event_description = $row_event['event_description'];
                            $event_image = $row_event['event_img']; // Fetch the main image path

                            // Display event details as list items with a form to change category
                            echo "<li>";
                        echo "<strong>$event_name</strong><br>";
                            echo "<img src='$event_image' alt='$event_name' style='max-width: 100px; max-height: 100px;'><br>"; // Display the main image
                            echo "Location: $event_location<br>";
                            echo "Description: $event_description<br>";

                            // Form to change category
                            echo "<form action='' method='post'>";
                            echo "<label for='new_category'>Move to category:</label>";
                          echo "<select name='new_category' id='new_category'>";
    // Fetch all categories and populate the dropdown excluding category ID 1
    $result_all_categories = mysqli_query($conn, "SELECT * FROM category WHERE category_id <> 1");
    while ($row_all_categories = mysqli_fetch_assoc($result_all_categories)) {
        $selected = ($row_all_categories['category_id'] == $category_id) ? 'selected' : '';
        echo "<option value='{$row_all_categories['category_id']}' $selected>{$row_all_categories['category_name']}</option>";
    }
    echo "</select>"; 

                            echo "<input type='hidden' name='event_id' value='$event_id'>";
                            echo "<input type='submit' name='move_event' value='Move'>";
                            echo "</form>";

                            echo "</li>";
                        }
                    } else {
                        // No events found for the current category
                        echo "<li>No events found for $category_name</li>";
                    }

                    echo "</ul>";
                }
            } else {
                // No categories found
                echo "<p>No categories found</p>";
            }

            // Close database connection
            mysqli_close($conn);
            ?>
        </div>
        <?php include 'admin_footer.php'; ?>
    </body>
    </html>
