<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <style>
body {
    font-family: Arial, sans-serif;
    background-image: linear-gradient(to bottom right, #333, #666); /* Gradient from dark grey to darker grey */
    background-size: cover;
    background-position: center;
    margin: 0;
    padding: 0;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}



.card {
    border: none;
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
    width: 600px;
    border-radius: 20px;
    background-image: linear-gradient(to bottom right, #ccc, #666); /* Gradient from light grey to dark grey */
    padding: 20px;
}


        .card-header {
            color: black; /* Header text color */
            font-weight: bold;
            text-align: center;
            padding: 10px 0; /* Padding for header */
            
        }

        .card-body {
            padding: 20px; /* Padding for body */
            margin-top: 20px;
        }

        .form-label {
            font-weight: bold;
            margin-bottom: 10px;
            display: block;
            text-align: left;
            text-align: center;
        }

        .form-control {
            width: 100%;
            height: 30px;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            padding: 5px;
        }

        .btn-primary {
            width: 100%;
            margin-top: 20px;
            background-color: #999999; /* Button background color */
            color: #fff; /* Button text color */
            border: none;
            padding: 10px; /* Button padding */
            border-radius: 20px;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #666666;
        }

        .btn-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #006666;
            text-decoration: none;
        }


        .alert {
            margin-top: 20px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="card-header">
            Login
        </div>
        <div class="card-body">
            <form method="post" action="home.php">
                                <label for="username_or_email" class="form-label">Username or Email</label>
                <input type="text" class="form-control" id="username_or_email" name="username_or_email">

                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password">

                <button type="submit" class="btn-primary" name="submit">Submit</button>
                <a href="register.php" class="btn-link">User Register</a>
            </form>
        </div>
    </div>
</body>

</html>