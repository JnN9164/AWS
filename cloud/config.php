<?php
$host = 'localhost';
$user = 'root';
$pass = ''; 
$db = 'graduation_store';

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}


$admin_username = 'admin';
$admin_password_plain = 'admin123';  
$admin_role = 'admin';


$check_admin_query = "SELECT * FROM users WHERE username = '$admin_username' LIMIT 1";
$check_result = mysqli_query($conn, $check_admin_query);

if (mysqli_num_rows($check_result) === 0) {
   
    $hashed_password = password_hash($admin_password_plain, PASSWORD_DEFAULT);
    $insert_admin_query = "INSERT INTO users (username, password, role) 
                           VALUES ('$admin_username', '$hashed_password', '$admin_role')";
    mysqli_query($conn, $insert_admin_query);
}

?>
