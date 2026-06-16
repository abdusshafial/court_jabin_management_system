<?php
include "config.php";

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>

<style>
.body-bg{ position:relative; }
body{
    margin:0;
    min-height:100vh;
    font-family:"Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg,#f0faf0 0%,#c7eac0 45%,#eaf7ea 100%);
    color:#0b2f14;
    position:relative;
    overflow-x:hidden;
}
body::before{
    content:"";
    position:fixed;
    inset:0;
    background: url('assets/scale-logo.svg') center top / 520px no-repeat;
    opacity:0.04;
    pointer-events:none;
    z-index:0;
}
.container{
    max-width:1100px;
    margin:0 auto;
    padding:36px 28px;
    text-align:center;
    position:relative;
    z-index:1;
}
.brand{
    display:inline-flex;
    align-items:center;
    gap:14px;
    justify-content:center;
    margin-bottom:18px;
}
.brand .icon{
    width:68px;
    height:68px;
    border-radius:12px;
    background: linear-gradient(180deg,#ffffff,#e9f8ea);
    box-shadow: 0 12px 26px rgba(6,28,14,.12);
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:24px;
    font-weight:800;
    color:#15461f;
}
.brand h1{
    margin:0;
    font-size:36px;
    letter-spacing:1px;
    text-transform:uppercase;
    text-shadow:0 2px 4px rgba(0,0,0,.24);
}
.subtitle{
    margin:8px 0 24px;
    color:#1d4a30;
    font-size:16px;
}
.card{
    background: #ffffff;
    border:1px solid rgba(15,60,20,.06);
    border-radius:18px;
    padding:28px;
    box-shadow:0 12px 34px rgba(10,30,14,.08);
}
.button-grid{
    display:grid;
    grid-template-columns: repeat(auto-fit,minmax(200px,1fr));
    gap:18px;
    margin-top:28px;
}
.button-grid a{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    min-height:64px;
    padding:14px 20px;
    background:#2b6f34;
    color:#fff;
    border-radius:12px;
    font-size:14px;
    font-weight:700;
    letter-spacing:0.6px;
    text-decoration:none;
    text-transform:uppercase;
    box-shadow: 0 8px 18px rgba(8,30,10,.08);
    transition: transform .18s ease, box-shadow .18s ease, background .18s ease;
}
.button-grid a.client{ background:#2a8f4d; }
.button-grid a.jabin{ background:#1c5d31; }
.button-grid a.advocate{ background:#134523; }
.button-grid a.logout{ background:#a72f2f; }
.button-grid a:hover{
    transform: translateY(-3px);
    box-shadow: 0 18px 30px rgba(0,0,0,.24);
}
.row{
    margin-top:20px;
}
small{
    color:rgba(255,255,255,.78);
}
</style>
</head>

<body>

<div class="container">
<div class="brand">
    <span class="icon">RC</span>
    <h1>Rangpur Judge Court<br><span style="font-weight:800; font-size:18px; display:block; margin-top:6px;">Thnana Pirgonj</span></h1>
</div>
<p class="subtitle">Welcome back, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>. Select a list or action.</p>
<div class="card">
    <h2 style="margin-top:0;font-size:24px;">Dashboard Options</h2>
    <p style="margin:0 0 12px;color:#2c5e3d;">Separate buttons, bold text, and a government theme.</p>
    <div class="button-grid">
        <a class="client" href="client_list.php">Client List</a>
        <a class="jabin" href="jabin_list.php">Jabin List</a>
        <a class="advocate" href="final_list.php">Advocate List</a>
        <a class="logout" href="logout.php">Logout</a>
    </div>
    <div class="row"><small>Peshkar: Md. Feroj Ahmed</small></div>
</div>
</div>

</body>
</html>
