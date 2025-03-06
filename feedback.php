<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Feedback Form</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">

  <style>
    @import url('https://fonts.googleapis.com/css?family=Montserrat:400,700');
    
    body {
      font-family: 'Montserrat', sans-serif;
      background-image: url('Websystemphp/10.webp');
      background-size: cover;
      background-position: center;
      padding-top: 120px; 
      width: 100%;
    }

    form {
      max-width: 820px;
      margin: 50px auto;
      background: #333;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      animation: fadeIn 0.5s ease-out;
    }

    .feedback-input {
      color: white;
      font-size: 18px;
      border-radius: 5px;
      line-height: 22px;
      background-color: #444;
      border: none;
      padding: 13px;
      margin-bottom: 15px;
      width: calc(100% - 26px);
      box-sizing: border-box;
      outline: 0;
    }

    .feedback-input:focus {
      background-color: #555;
    }

    textarea {
      height: 150px;
      line-height: 150%;
      resize: vertical;
    }

    [type='submit'] {
      width: 100%;
      background: #000000;
      border-radius: 5px;
      border: 0;
      cursor: pointer;
      color: white;
      font-size: 24px;
      padding: 10px;
      transition: background-color 0.3s;
      font-weight: 700;
    }

    [type='submit']:hover {
      background: #555;
    }

    .feedback-input::placeholder {
      color: #aaa;
      font-weight: 400;
    }

    .feedback-input:focus::placeholder {
      color: #fff;
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    /* Footer styles */
    footer {
      position: relative;
      bottom: 0;
      width: 100%;
      display: block;
      clear: both;
    }

    /* Header styles */
    nav {
      position: fixed;
      top: 0;
      z-index: 100;
      width: 100%;
    }
  </style>
</head>
<body>
  <?php include('header.php'); ?>
  <br><br><br><br>

  <!-- Content -->
  <?php 
  $servername = "localhost";
  $username = "root";
  $password = "";
  $database = "login";

  $connection = new mysqli($servername, $username, $password, $database);

  if ($connection->connect_error) {
      die("Connection failed: " . $connection->connect_error);
  }
  ?>

  <h1 style="text-align: center; color: white;">CONFESSION WALL & FEEDBACK FORM</h1>
  <form id="feedbackForm">
    <input name="name" type="text" class="feedback-input" placeholder="Subject" required>
    <textarea name="comment" class="feedback-input" placeholder="Comment" required></textarea>
    <input type="submit" value="SUBMIT" name="submit">
  </form>
  <br><br><br><br>

  <?php include('footer.php'); ?>

  <script>
    document.getElementById('feedbackForm').addEventListener('submit', function(event) {
      event.preventDefault();

      var formData = new FormData(this);
      
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'feedback.php', true);
      xhr.onload = function () {
        if (xhr.status === 200) {
          alert('FEEDBACK SUCCESSFUL.');
          document.getElementById('feedbackForm').reset();
        } else {
          alert('Error: ' + xhr.responseText);
        }
      };
      xhr.send(formData);
    });

    document.querySelectorAll('.feedback-input').forEach(input => {
      input.addEventListener('focus', function() {
        input.style.backgroundColor = '#555';
      });

      input.addEventListener('blur', function() {
        input.style.backgroundColor = '#444';
      });
    });
  </script>
</body>
</html>
