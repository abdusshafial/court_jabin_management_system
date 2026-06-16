<?php
include 'config.php';
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($username === '') {
        $errors[] = 'Username is required.';
    }
    if ($password === '') {
        $errors[] = 'Password is required.';
    }
    if ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        $username = mysqli_real_escape_string($conn, $username);
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $checkSql = "SELECT id FROM users WHERE username='$username' LIMIT 1";
        $checkResult = mysqli_query($conn, $checkSql);

        if ($checkResult && mysqli_num_rows($checkResult) > 0) {
            $errors[] = 'Username already exists.';
        } else {
            $insertSql = "INSERT INTO users (username, password) VALUES ('$username', '$passwordHash')";
            if (mysqli_query($conn, $insertSql)) {
                $success = 'Account created successfully. You may now login.';
            } else {
                $errors[] = 'Unable to register account. Please try again.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register - Rangpur Judge Court</title>
<style>
body{
    margin:0;
    min-height:100vh;
    background: linear-gradient(135deg,#eaf7ea 0%,#9fcf9a 40%,#113d21 100%);
    font-family:"Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    color:#fff;
    position:relative;
    overflow-x:hidden;
}
body::before{
    content:"";
    position:fixed;
    inset:0;
    background: url('assets/scale-logo.svg') center top / 420px no-repeat;
    opacity:0.06;
    pointer-events:none;
    z-index:0;
}
.page-wrap{
    display:flex;
    align-items:center;
    justify-content:center;
    min-height:100vh;
    padding:20px;
    position:relative;
    z-index:1;
}
.box{
    width:420px;
    max-width:100%;
    background: linear-gradient(180deg, rgba(255,255,255,0.97), rgba(250,250,250,0.96));
    color:#0d2e12;
    border:1px solid rgba(10,50,20,.08);
    padding:32px;
    border-radius:16px;
    box-shadow: 0 12px 40px rgba(6,28,14,.22);
}
.logo{
    display:block;
    margin:0 auto 12px;
    font-size:26px;
    font-weight:800;
    letter-spacing:1px;
    text-transform:uppercase;
    color:#124620;
    text-align:center;
}
h1{
    text-align:center;
    margin:0 0 6px;
    font-size:28px;
    color:#124620;
}
.subtitle{
    text-align:center;
    margin:0 0 24px;
    font-size:14px;
    color:#486f4c;
}
.message{
    margin:0 0 16px;
    padding:12px 14px;
    border-radius:12px;
    background:rgba(229,247,229,.95);
    color:#1f4f1f;
    border:1px solid rgba(30,96,30,.16);
}
.error{
    margin:0 0 12px;
    padding:12px 14px;
    border-radius:12px;
    background:rgba(255,120,120,.16);
    color:#8a1515;
    border:1px solid rgba(255,160,160,.35);
}
input{
    width:100%;
    padding:14px 16px;
    margin:10px 0;
    border-radius:12px;
    border:1px solid rgba(200,200,200,.5);
    background: rgba(255,255,255,.96);
    color:#0d2e12;
    font-size:16px;
    box-sizing:border-box;
}
button{
    width:100%;
    padding:14px 16px;
    margin-top:12px;
    border:none;
    border-radius:14px;
    background: linear-gradient(145deg,#1f7d47,#1a5c33);
    color:#fff;
    font-size:16px;
    font-weight:700;
    letter-spacing:1px;
    cursor:pointer;
    transition: transform .25s ease, box-shadow .25s ease, background .25s ease;
    box-shadow: 0 10px 20px rgba(0,0,0,.18);
    text-transform:uppercase;
}
button:hover{
    transform: translateY(-2px);
    box-shadow: 0 16px 26px rgba(0,0,0,.24);
    background: linear-gradient(145deg,#2a964d,#1b7030);
}
a.link{
    display:inline-block;
    margin-top:14px;
    color:#124620;
    text-decoration:none;
    font-weight:700;
}
a.link:hover{
    text-decoration:underline;
}
</style>
</head>
<body>
<div class="page-wrap">
<div class="box">
    <span class="logo">Govt Court</span>
    <h1>Register Account</h1>
    <p class="subtitle">Create a judicial user account for Rangpur Judge Court.</p>
    <?php if ($success): ?>
        <div class="message"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    <?php foreach ($errors as $error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endforeach; ?>
    <form action="register.php" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit">Register</button>
    </form>
    <a class="link" href="login.php">Already have an account? Login here</a>
</div>
</div>
</body>
</html>
