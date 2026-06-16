<?php
include 'config.php';

// Redirect logged-in users to dashboard, others to login
if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
    header('Location: dashboard.php');
    exit();
}

header('Location: login.php');
exit();
