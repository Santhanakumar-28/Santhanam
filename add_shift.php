<?php
session_start();
include "db.php";
if(!isset($_SESSION['admin'])) header("Location: login.html");

/* ADD SHIFT */
if(isset($_POST['add'])){
    mysqli_query($conn,"INSERT INTO shifts(shift_name,start_time,end_time)
    VALUES('".$_POST['name']."','".$_POST['start']."','".$_POST['end']."')");
}

/* REMOVE SHIFT */
if(isset($_GET['delete'])){
    mysqli_query($conn,"DELETE FROM shifts WHERE id=".$_GET['delete']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Shift - SRM</title>
<link rel="stylesheet" href="style.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<?php include "sidebar.php"; ?>

<div class="content">
<h2>⏰ Manage Shifts</h2>

<div class="card">
    <div class="card-header">
        <h3>➕ Add New Shift</h3>
    </div>
    <form method="post">
        <input name="name" placeholder="📝 Shift Name" required>
        <input type="time" name="start" required style="min-width: 150px;">
        <input type="time" name="end" required style="min-width: 150px;">
        <button name="add" class="primary">Add Shift</button>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h3>📋 Current Shifts</h3>
    </div>
    <table>
    <tr><th>Name</th><th>Start Time</th><th>End Time</th><th>Action</th></tr>
<?php
$r=mysqli_query($conn,"SELECT * FROM shifts");
while($row=mysqli_fetch_assoc($r)){
    echo "<tr>
    <td><strong>".htmlspecialchars($row['shift_name'])."</strong></td>
    <td>".htmlspecialchars($row['start_time'])."</td>
    <td>".htmlspecialchars($row['end_time'])."</td>
    <td><a href='?delete=".htmlspecialchars($row['id'])."'>🗑️ Remove</a></td>
    </tr>";
}
?>
</table>
</div>
</div>
</body>
</html>
