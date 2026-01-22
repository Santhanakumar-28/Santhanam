<?php
session_start();
include "db.php";
if(!isset($_SESSION['admin'])) header("Location: login.html");

/* ADD MACHINE */
if(isset($_POST['add'])){
    mysqli_query($conn,"INSERT INTO machines(machine_id,machine_name)
    VALUES('".$_POST['mid']."','".$_POST['mname']."')");
}

/* REMOVE MACHINE */
if(isset($_GET['delete'])){
    mysqli_query($conn,"DELETE FROM machines WHERE machine_id='".$_GET['delete']."'");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Machine - SRM</title>
<link rel="stylesheet" href="style.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<?php include "sidebar.php"; ?>

<div class="content">
<h2>🔧 Manage Machines</h2>

<div class="card">
    <div class="card-header">
        <h3>➕ Add New Machine</h3>
    </div>
    <form method="post">
        <input name="mid" placeholder="🆔 Machine ID" required>
        <input name="mname" placeholder="📝 Machine Name" required>
        <button name="add" class="primary">Add Machine</button>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h3>📋 Current Machines</h3>
    </div>
    <table>
    <tr><th>ID</th><th>Name</th><th>Action</th></tr>
<?php
$r=mysqli_query($conn,"SELECT * FROM machines");
while($row=mysqli_fetch_assoc($r)){
    echo "<tr>
    <td><strong>".htmlspecialchars($row['machine_id'])."</strong></td>
    <td>".htmlspecialchars($row['machine_name'])."</td>
    <td><a href='?delete=".htmlspecialchars($row['machine_id'])."'>🗑️ Remove</a></td>
    </tr>";
}
?>
</table>
</div>
</div>
</body>
</html>
