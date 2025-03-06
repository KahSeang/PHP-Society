<?php

/* 
 *
 * 
 *  WELCOME TO OK SOCIETY I HOPE YOU ARE OK WITH THIS PAGE
 * 
 * 
 * 
 * 1)
 * REGISTER PAGE
 * PHP MAILER FUNCTIONN  *
 * -use own email and go to the app password generate it and paste only
 * -send account active link
 * -
 * CAPTCHA
 * -go to the website generate the side key and secret key
 * 
 * PHP CODE NOTE
 *    $sql = "INSERT INTO users (username, password, email, activation_code, is_active) VALUES (?, ?, ?, ?, 0)";
 *    $stmt = $conn->prepare($sql);    
 *    $stmt->bind_param("ssss", $username, $password, $email, $activation_token); PASS STRING TO THE PARAMETER often  use in update delete but here use because the ????      // Try to execute the prepared statement
 *   
 * $stmt is not a fixed variable name; FULL NAME STATEMENT some time use  $statement
 *   Using bind_param not only improves security but also makes your application more robust and less prone to errors due to improper data handling. This method should be used whenever input is taken from untrusted sources, such as user input.
 *   WHEN INSERT DONNEED THE RESULT CODE 
 * 
 * 2)LOGIN PAGE
 * ADMIN AND USER REGISTER COMBINE!!!
 * 
 *  $user_query = "SELECT * FROM users WHERE email='$email'";   从POST那边拿EMAIL了
 *  $admin_query = "SELECT * FROM admin WHERE email='$email'"; 
 *  
 *   $user_result = mysqli_query($connection, $user_query); 
 *   $admin_result = mysqli_query($connection, $admin_query);
 * 
 * list of sql code才进来这里COMPARE IF == 1
 *   mysqli_num_rows 
 *   mysqli_fetch_assoc
 * 
 * 
 * 3)HOME PAGE  
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * ADMIN PAGE
 * ONG WENG KANG 
 * REPORT
 * 
 */

