<?php
include 'adminheader.php'; 
include 'adminsidebar.php'; 
// Start the session
session_start();

// Initialize variable to store saved search queries
$savedSearch = "";

// Check if the search_query cookie exists
if (isset($_COOKIE['search_query'])) {
    // Retrieve the existing search queries from the cookie
    $searchQueries = explode('|', $_COOKIE['search_query']);

    // Check if there are any search queries
    if (!empty($searchQueries)) {
        // Build the HTML content for saved search queries
        $savedSearch .= "<h3>Saved Search Queries:</h3>";
        $savedSearch .= "<ul>";
        // Iterate over the array to build list items for each search query
        foreach ($searchQueries as $query) {
            $savedSearch .= "<li>$query</li>";
        }
        $savedSearch .= "</ul>";
    } else {
        // No saved search queries found
        $savedSearch = "No saved search queries found.";
    }
} else {
    // No search_query cookie found
    $savedSearch = "No saved search queries found.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Saved Search Cookie</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            margin-top: 0;
            color: #333;
        }

        p {
            color: #666;
        }

        .cookie-content {
            margin-top: 20px;
        }

        .cookie-content ul {
            list-style-type: none;
            padding: 0;
        }

        .cookie-content ul li {
            margin-bottom: 5px;
            padding: 8px 12px;
            background-color: #f9f9f9;
            border-radius: 3px;
        }

        /* Fade-in animation */
        .fade-in {
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content fade-in">
            <h1>Admin - View Saved Search Cookie</h1>
            <p>This page displays the content of the saved search cookie:</p>
            <div class="cookie-content">
                <?php echo $savedSearch; ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        // jQuery code for fade-in animation
        $(document).ready(function() {
            $('.fade-in').animate({ opacity: 1 }, 400);
        });
    </script>
</body>
</html>
