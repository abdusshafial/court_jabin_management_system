<?php
include("config.php");

$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username='$username' LIMIT 1";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0){
    $row = mysqli_fetch_assoc($result);
    $storedPassword = $row['password'];

    if ((function_exists('password_verify') && password_verify($password, $storedPassword)) || $password === $storedPassword) {
        session_regenerate_id(true);
        $_SESSION['username'] = $row['username'];
        header("Location: dashboard.php");
        exit();
    }
}

header("Location: login.php?error=1");
exit();
