<?php
session_start();
include "db.php";
if(!isset($_SESSION['admin'])) header("Location: login.html");

/* ADD OPERATOR */
if(isset($_POST['add'])){
    mysqli_query($conn,"INSERT INTO operators(username,password)
    VALUES('".$_POST['user']."','".$_POST['pass']."')");
}

/* REMOVE OPERATOR */
if(isset($_GET['delete'])){
    mysqli_query($conn,"DELETE FROM operators WHERE username='".$_GET['delete']."'");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Operator - SRM</title>
<link rel="stylesheet" href="style.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<?php include "sidebar.php"; ?>

<div class="content">
<h2>👥 Manage Operators</h2>

<div class="card">
    <div class="card-header">
        <h3>➕ Add New Operator</h3>
    </div>
    <form method="post">
        <input name="user" placeholder="👤 Username" required>
        <input name="pass" type="password" placeholder="🔒 Password" required>
        <button name="add" class="primary">Add Operator</button>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h3>📋 Current Operators</h3>
    </div>
    <table>
    <tr><th>Username</th><th>Action</th></tr>
<?php
$r=mysqli_query($conn,"SELECT * FROM operators");
while($row=mysqli_fetch_assoc($r)){
    echo "<tr>
    <td><strong>".htmlspecialchars($row['username'])."</strong></td>
    <td><a href='?delete=".htmlspecialchars($row['username'])."'>🗑️ Remove</a></td>
    </tr>";
}
?>
</table>
</div>
</div>
</body>
</html>
