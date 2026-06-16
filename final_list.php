<?php
include "config.php";

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_advocate'])) {
    $name = trim($_POST['name'] ?? '');
    $status = trim($_POST['status'] ?? 'Active');
    $village = trim($_POST['village'] ?? '');
    $postcode = trim($_POST['postcode'] ?? '');
    if ($name === '' || $village === '' || $postcode === '') {
        $message = 'Please enter name, village, and postcode.';
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO final_list (name, status, village, postcode) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'ssss', $name, $status, $village, $postcode);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: final_list.php?added=1");
            exit();
        }
        $message = 'Could not add the advocate. The name may already exist.';
        mysqli_stmt_close($stmt);
    }
}

if (isset($_GET['added'])) {
    $message = 'Advocate added successfully.';
}

$query = "SELECT id, name, status, village, postcode FROM final_list ORDER BY id ASC";
$result = mysqli_query($conn, $query);
?>


<!DOCTYPE html>
<html>
<head>
<title>Advocate List</title>

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
.page{
    max-width:980px;
    margin:0 auto;
    padding:36px 26px;
    position:relative;
    z-index:1;
}
.header{
    display:flex;
    align-items:center;
    justify-content:space-between;
    flex-wrap:wrap;
    gap:16px;
}
.header h1{
    margin:0;
    font-size:32px;
    text-transform:uppercase;
}
.button{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    background:#1d6c3e;
    color:#fff;
    text-decoration:none;
    padding:12px 18px;
    border-radius:14px;
    font-weight:700;
    letter-spacing:1px;
}
.card{
    background:#fff;
    border:1px solid rgba(15,60,20,.06);
    border-radius:14px;
    padding:22px;
    box-shadow:0 12px 28px rgba(10,30,14,.06);
    margin-top:20px;
}
input,
select{
    width:100%;
    padding:12px 14px;
    border-radius:12px;
    border:1px solid rgba(15,80,30,.18);
    background:#f8fff5;
    color:#0b2f14;
    font-size:15px;
    box-sizing:border-box;
}
button{
    padding:14px 20px;
    border:none;
    border-radius:14px;
    background:#2b6f34;
    color:#fff;
    font-size:15px;
    font-weight:700;
    letter-spacing:.8px;
    cursor:pointer;
    transition:.18s ease;
}
button:hover{
    background:#24662f;
}
table{
    width:100%;
    border-collapse:collapse;
    margin-top:16px;
}
th, td{
    padding:12px 14px;
    border-bottom:1px solid rgba(220,230,220,.6);
    text-align:left;
}
th{
    text-transform:uppercase;
    font-size:13px;
    letter-spacing:1px;
    color:#d7f2cf;
}
tr:hover{
    background:rgba(255,255,255,.08);
}
</style>
</head>

<body>

<div class="page">
    <div class="header">
        <div>
            <h1>Advocate List</h1>
            <p style="margin:8px 0 0; color:#2f5d38;">Peshkar: Md. Feroj Ahmed</p>
        </div>
        <a class="button" href="dashboard.php">Back to Dashboard</a>
    </div>
    <div class="card">
        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form method="POST" style="display:flex;flex-wrap:wrap;gap:16px;align-items:flex-end;margin-bottom:22px;">
            <div style="flex:1;min-width:220px;">
                <label style="display:block;margin-bottom:6px;color:#315738;font-weight:700;">Advocate name</label>
                <input type="text" name="name" placeholder="Enter advocate name" required>
            </div>
            <div style="flex:1;min-width:200px;">
                <label style="display:block;margin-bottom:6px;color:#315738;font-weight:700;">Status</label>
                <select name="status">
                    <option>Active</option>
                    <option>Pending</option>
                    <option>Complete</option>
                </select>
            </div>
            <div style="flex:1;min-width:220px;">
                <label style="display:block;margin-bottom:6px;color:#315738;font-weight:700;">Village (Pirgonj)</label>
                <input type="text" name="village" placeholder="Enter village name" required>
            </div>
            <div style="flex:1;min-width:200px;">
                <label style="display:block;margin-bottom:6px;color:#315738;font-weight:700;">Postcode</label>
                <input type="text" name="postcode" placeholder="Enter postcode" required>
            </div>
            <div style="min-width:140px;">
                <input type="hidden" name="add_advocate" value="1">
                <button type="submit">Add Advocate</button>
            </div>
        </form>
        <p style="margin:0 0 18px;color:#2f5d38;">This section shows advocate ID, name, status, village, and postcode for the court system.</p>
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Village</th>
                    <th>Postcode</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td><?php echo htmlspecialchars($row['village']); ?></td>
                    <td><?php echo htmlspecialchars($row['postcode']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="message">No records found. Please import the SQL file or add data in the database.</div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
