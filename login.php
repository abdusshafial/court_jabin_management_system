<?php
$error = '';
if (isset($_GET['error']) && $_GET['error'] === '1') {
    $error = 'Invalid username or password.';
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Rangpur Judge Court</title>

<style>
.body-bg{
    position:relative;
}
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
    background: url('assets/gov-logo.svg') center top / 420px no-repeat;
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
    background: linear-gradient(180deg, rgba(255,255,255,0.95), rgba(250,250,250,0.9));
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
.logo::after{
    content:"";
    position:absolute;
    inset:0;
    z-index:-1;
    transform: translate(6px, 6px);
    border-radius: 16px;
    background: rgba(16,55,25,.2);
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
    color:#2f5d38;
}
.error{
    margin:0 0 12px;
    padding:12px 14px;
    border-radius:12px;
    background:rgba(255,120,120,.16);
    color:#ffe3e3;
    border:1px solid rgba(255,160,160,.35);
    text-align:center;
}
input{
    width:100%;
    padding:14px 16px;
    margin:10px 0;
    border-radius:12px;
    border:1px solid rgba(255,255,255,.35);
    background: rgba(255,255,255,.92);
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
    box-shadow: 0 10px 20px rgba(0,0,0,.22);
    text-transform:uppercase;
}
button:hover{
    transform: translateY(-2px);
    box-shadow: 0 16px 26px rgba(0,0,0,.28);
    background: linear-gradient(145deg,#2a964d,#1b7030);
}
</style>
</head>

<body>

<div class="page-wrap">
<div class="box">
<span class="logo">Govt Court</span>
<h1>Rangpur Judge Court</h1>
<p class="subtitle">Government lover style login with 3D logo</p>
<?php if ($error): ?>
    <div class="error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<form action="login_process.php" method="POST">
<input type="text" name="username" placeholder="Username" required>
<input type="password" name="password" placeholder="Password" required>
<button type="submit">Login</button>
</form>
<a href="register.php" style="display:inline-block;margin-top:14px;color:#124620;font-weight:700;text-decoration:none;">Create new account</a>
</div>
</div>

</body>
</html>
