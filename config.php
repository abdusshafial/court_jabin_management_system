<?php

session_start();

$host = "localhost";
$user = "root";
$password = "";
$database = "rangpur_court";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

?>