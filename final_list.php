<?php
include "config.php";

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

function parse_money_to_cents($value) {
    if (!preg_match('/^\d{1,10}(?:\.\d{1,2})?$/D', $value)) {
        return null;
    }

    $parts = explode('.', $value, 2);
    $whole = (int) $parts[0];
    $fraction = isset($parts[1]) ? (int) str_pad($parts[1], 2, '0') : 0;
    return ($whole * 100) + $fraction;
}

$advocateColumns = [
    'qualification' => "VARCHAR(255) NOT NULL DEFAULT ''",
    'bar_council_registration' => "VARCHAR(100) NOT NULL DEFAULT ''",
    'bar_association_member_id' => "VARCHAR(100) NOT NULL DEFAULT ''",
    'senior_advocate_name' => "VARCHAR(255) NOT NULL DEFAULT ''",
    'mobile_number' => "VARCHAR(50) NOT NULL DEFAULT ''",
    'alternate_contact_number' => "VARCHAR(50) NOT NULL DEFAULT ''",
    'email' => "VARCHAR(255) NOT NULL DEFAULT ''",
    'chamber_address' => "TEXT NULL",
    'residential_address' => "TEXT NULL",
    'vakalatnama_status' => "VARCHAR(30) NOT NULL DEFAULT 'Not submitted'",
    'roll_serial_number' => "VARCHAR(100) NOT NULL DEFAULT ''",
    'court_room_number' => "VARCHAR(100) NOT NULL DEFAULT ''",
    'specialization' => "VARCHAR(255) NOT NULL DEFAULT ''",
    'fee_basis' => "VARCHAR(30) NOT NULL DEFAULT 'Per case'",
    'fee_amount' => "DECIMAL(12,2) NOT NULL DEFAULT 0.00",
    'paid_fee' => "DECIMAL(12,2) NOT NULL DEFAULT 0.00",
    'due_fee' => "DECIMAL(12,2) NOT NULL DEFAULT 0.00"
];

$columnsResult = mysqli_query($conn, "SHOW COLUMNS FROM final_list");
if (!$columnsResult) {
    die("Could not inspect advocate table: " . htmlspecialchars(mysqli_error($conn)));
}
$existingColumns = [];
while ($column = mysqli_fetch_assoc($columnsResult)) {
    $existingColumns[$column['Field']] = true;
}
mysqli_free_result($columnsResult);

foreach ($advocateColumns as $columnName => $columnDefinition) {
    if (!isset($existingColumns[$columnName])) {
        $alterQuery = "ALTER TABLE final_list ADD COLUMN `$columnName` $columnDefinition";
        if (!mysqli_query($conn, $alterQuery)) {
            die("Could not update advocate table: " . htmlspecialchars(mysqli_error($conn)));
        }
    }
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_advocate'])) {
    $name = trim($_POST['name'] ?? '');
    $status = trim($_POST['status'] ?? 'Active');
    $qualification = trim($_POST['qualification'] ?? '');
    $barCouncilRegistration = trim($_POST['bar_council_registration'] ?? '');
    $barAssociationMemberId = trim($_POST['bar_association_member_id'] ?? '');
    $seniorAdvocateName = trim($_POST['senior_advocate_name'] ?? '');
    $mobileNumber = trim($_POST['mobile_number'] ?? '');
    $alternateContactNumber = trim($_POST['alternate_contact_number'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $chamberAddress = trim($_POST['chamber_address'] ?? '');
    $residentialAddress = trim($_POST['residential_address'] ?? '');
    $vakalatnamaStatus = trim($_POST['vakalatnama_status'] ?? 'Not submitted');
    $rollSerialNumber = trim($_POST['roll_serial_number'] ?? '');
    $courtRoomNumber = trim($_POST['court_room_number'] ?? '');
    $specialization = trim($_POST['specialization'] ?? '');
    $feeBasis = trim($_POST['fee_basis'] ?? 'Per case');
    $feeCents = parse_money_to_cents(trim($_POST['fee_amount'] ?? '0'));
    $paidFeeCents = parse_money_to_cents(trim($_POST['paid_fee'] ?? '0'));

    if ($name === '') {
        $message = 'Please enter the advocate full name.';
    } elseif ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Please enter a valid email address.';
    } elseif (!in_array($vakalatnamaStatus, ['Submitted', 'Not submitted', 'Pending'], true)) {
        $message = 'Please select a valid vakalatnama status.';
    } elseif (!in_array($feeBasis, ['Per case', 'Per hearing'], true)) {
        $message = 'Please select a valid fee basis.';
    } elseif ($feeCents === null || $paidFeeCents === null) {
        $message = 'Fee amounts must be non-negative numbers with up to two decimal places.';
    } elseif ($paidFeeCents > $feeCents) {
        $message = 'Paid fee cannot be greater than the total fee.';
    } else {
        $feeAmount = sprintf('%d.%02d', intdiv($feeCents, 100), $feeCents % 100);
        $paidFee = sprintf('%d.%02d', intdiv($paidFeeCents, 100), $paidFeeCents % 100);
        $dueFeeCents = $feeCents - $paidFeeCents;
        $dueFee = sprintf('%d.%02d', intdiv($dueFeeCents, 100), $dueFeeCents % 100);
        $stmt = mysqli_prepare($conn, "INSERT INTO final_list (
            name, status, qualification, bar_council_registration, bar_association_member_id,
            senior_advocate_name, mobile_number, alternate_contact_number, email,
            chamber_address, residential_address, vakalatnama_status, roll_serial_number,
            court_room_number, specialization, fee_basis, fee_amount, paid_fee, due_fee
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            die("Could not prepare advocate record: " . htmlspecialchars(mysqli_error($conn)));
        }
        mysqli_stmt_bind_param(
            $stmt,
            str_repeat('s', 19),
            $name,
            $status,
            $qualification,
            $barCouncilRegistration,
            $barAssociationMemberId,
            $seniorAdvocateName,
            $mobileNumber,
            $alternateContactNumber,
            $email,
            $chamberAddress,
            $residentialAddress,
            $vakalatnamaStatus,
            $rollSerialNumber,
            $courtRoomNumber,
            $specialization,
            $feeBasis,
            $feeAmount,
            $paidFee,
            $dueFee
        );
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: final_list.php?added=1");
            exit();
        }
        $message = 'Could not add the advocate: ' . mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);
    }
}

if (isset($_GET['added'])) {
    $message = 'Advocate added successfully.';
}

$query = "SELECT id, name, status, qualification, bar_council_registration,
    bar_association_member_id, senior_advocate_name, mobile_number,
    alternate_contact_number, email, chamber_address, residential_address,
    vakalatnama_status, roll_serial_number, court_room_number, specialization,
    fee_basis, fee_amount, paid_fee, due_fee
    FROM final_list ORDER BY id ASC";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Could not load advocate records: " . htmlspecialchars(mysqli_error($conn)));
}
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
    max-width:1200px;
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
select,
textarea{
    width:100%;
    padding:12px 14px;
    border-radius:12px;
    border:1px solid rgba(15,80,30,.18);
    background:#f8fff5;
    color:#0b2f14;
    font-size:15px;
    box-sizing:border-box;
    font-family:inherit;
}
textarea{
    min-height:82px;
    resize:vertical;
}
.form-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:16px;
    align-items:end;
    margin-bottom:22px;
}
.form-section-title{
    grid-column:1 / -1;
    margin:10px 0 -6px;
    color:#1d6c3e;
    font-size:18px;
}
.field label{
    display:block;
    margin-bottom:6px;
    color:#315738;
    font-weight:700;
}
.table-wrap{
    overflow-x:auto;
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
        <form method="POST" class="form-grid">
            <h2 class="form-section-title">Personal &amp; Professional Details</h2>
            <div class="field">
                <label for="name">Full name</label>
                <input id="name" type="text" name="name" placeholder="Enter advocate full name" required>
            </div>
            <div class="field">
                <label for="qualification">Title &amp; educational qualifications</label>
                <input id="qualification" type="text" name="qualification" placeholder="e.g. Advocate, LL.B, LL.M">
            </div>
            <div class="field">
                <label for="bar_council_registration">Bar Council registration number</label>
                <input id="bar_council_registration" type="text" name="bar_council_registration">
            </div>
            <div class="field">
                <label for="bar_association_member_id">Bar Association member number</label>
                <input id="bar_association_member_id" type="text" name="bar_association_member_id">
            </div>
            <div class="field">
                <label for="senior_advocate_name">Senior advocate / chamber supervisor</label>
                <input id="senior_advocate_name" type="text" name="senior_advocate_name">
            </div>
            <div class="field">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option>Active</option>
                    <option>Pending</option>
                    <option>Complete</option>
                </select>
            </div>

            <h2 class="form-section-title">Contact Information</h2>
            <div class="field">
                <label for="mobile_number">Mobile number</label>
                <input id="mobile_number" type="tel" name="mobile_number">
            </div>
            <div class="field">
                <label for="alternate_contact_number">Alternative contact number</label>
                <input id="alternate_contact_number" type="tel" name="alternate_contact_number">
            </div>
            <div class="field">
                <label for="email">Email address</label>
                <input id="email" type="email" name="email">
            </div>
            <div class="field">
                <label for="chamber_address">Chamber address</label>
                <textarea id="chamber_address" name="chamber_address"></textarea>
            </div>
            <div class="field">
                <label for="residential_address">Residential address</label>
                <textarea id="residential_address" name="residential_address"></textarea>
            </div>

            <h2 class="form-section-title">Case &amp; Court Details</h2>
            <div class="field">
                <label for="vakalatnama_status">Vakalatnama status</label>
                <select id="vakalatnama_status" name="vakalatnama_status">
                    <option>Not submitted</option>
                    <option>Submitted</option>
                    <option>Pending</option>
                </select>
            </div>
            <div class="field">
                <label for="roll_serial_number">Court roll / serial number</label>
                <input id="roll_serial_number" type="text" name="roll_serial_number">
            </div>
            <div class="field">
                <label for="court_room_number">Court room number(s)</label>
                <input id="court_room_number" type="text" name="court_room_number">
            </div>
            <div class="field">
                <label for="specialization">Specialization</label>
                <input id="specialization" type="text" name="specialization" placeholder="e.g. Civil, Criminal, Writ, Tax">
            </div>

            <h2 class="form-section-title">Financial Records</h2>
            <div class="field">
                <label for="fee_basis">Fee basis</label>
                <select id="fee_basis" name="fee_basis">
                    <option>Per case</option>
                    <option>Per hearing</option>
                </select>
            </div>
            <div class="field">
                <label for="fee_amount">Total fee / honorarium</label>
                <input id="fee_amount" type="number" name="fee_amount" min="0" step="0.01" value="0">
            </div>
            <div class="field">
                <label for="paid_fee">Paid fee</label>
                <input id="paid_fee" type="number" name="paid_fee" min="0" step="0.01" value="0">
            </div>
            <div class="field">
                <label for="due_fee">Due fee (calculated)</label>
                <input id="due_fee" type="text" value="0.00" readonly>
            </div>
            <div style="min-width:140px;">
                <input type="hidden" name="add_advocate" value="1">
                <button type="submit">Add Advocate</button>
            </div>
        </form>
        <p style="margin:0 0 18px;color:#2f5d38;">Due fee is calculated as total fee minus paid fee. Client and Jabin lists keep their existing fields.</p>
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full name</th>
                    <th>Status</th>
                    <th>Qualifications</th>
                    <th>Bar Council no.</th>
                    <th>Bar Association member no.</th>
                    <th>Senior advocate</th>
                    <th>Mobile</th>
                    <th>Alternative contact</th>
                    <th>Email</th>
                    <th>Chamber address</th>
                    <th>Residential address</th>
                    <th>Vakalatnama status</th>
                    <th>Roll / serial no.</th>
                    <th>Court room no.</th>
                    <th>Specialization</th>
                    <th>Fee basis</th>
                    <th>Total fee</th>
                    <th>Paid fee</th>
                    <th>Due fee</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td><?php echo htmlspecialchars($row['qualification']); ?></td>
                    <td><?php echo htmlspecialchars($row['bar_council_registration']); ?></td>
                    <td><?php echo htmlspecialchars($row['bar_association_member_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['senior_advocate_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['mobile_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['alternate_contact_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['chamber_address'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($row['residential_address'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($row['vakalatnama_status']); ?></td>
                    <td><?php echo htmlspecialchars($row['roll_serial_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['court_room_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['specialization']); ?></td>
                    <td><?php echo htmlspecialchars($row['fee_basis']); ?></td>
                    <td><?php echo htmlspecialchars($row['fee_amount']); ?></td>
                    <td><?php echo htmlspecialchars($row['paid_fee']); ?></td>
                    <td><?php echo htmlspecialchars($row['due_fee']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        </div>
        <?php else: ?>
        <div class="message">No records found. Please import the SQL file or add data in the database.</div>
        <?php endif; ?>
    </div>
</div>

<script>
const feeAmountInput = document.getElementById('fee_amount');
const paidFeeInput = document.getElementById('paid_fee');
const dueFeeInput = document.getElementById('due_fee');

function updateDueFee() {
    const fee = Number(feeAmountInput.value) || 0;
    const paid = Number(paidFeeInput.value) || 0;
    dueFeeInput.value = Math.max(0, fee - paid).toFixed(2);
}

feeAmountInput.addEventListener('input', updateDueFee);
paidFeeInput.addEventListener('input', updateDueFee);
</script>

</body>
</html>
