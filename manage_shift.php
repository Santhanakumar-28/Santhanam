<?php
session_start();
include "db.php";
if(!isset($_SESSION['admin'])) header("Location: login.html");

/* ALLOCATE */
if(isset($_POST['allocate'])){
    mysqli_query($conn,"INSERT INTO allocations(operator_username,machine_id,shift_id)
    VALUES('".$_POST['operator']."','".$_POST['machine']."',".$_POST['shift'].")");
}

/* REMOVE ALLOCATION */
if(isset($_GET['delete'])){
    mysqli_query($conn,"DELETE FROM allocations WHERE id=".$_GET['delete']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Shifts - SRM</title>
<link rel="stylesheet" href="style.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<?php include "sidebar.php"; ?>

<div class="content">
<h2>📋 Manage Shift Allocations</h2>

<div class="card">
    <div class="card-header">
        <h3>➕ Allocate Operator to Machine & Shift</h3>
    </div>
    <form method="post">
        <select name="operator" required>
            <option value="">👤 Select Operator</option>
            <?php
            $o=mysqli_query($conn,"SELECT username FROM operators");
            while($r=mysqli_fetch_assoc($o)){
                echo "<option value='".htmlspecialchars($r['username'])."'>".htmlspecialchars($r['username'])."</option>";
            }
            ?>
        </select>

        <select name="machine" required>
            <option value="">🔧 Select Machine</option>
            <?php
            $m=mysqli_query($conn,"SELECT * FROM machines");
            while($r=mysqli_fetch_assoc($m)){
                echo "<option value='".htmlspecialchars($r['machine_id'])."'>".htmlspecialchars($r['machine_id'])." - ".htmlspecialchars($r['machine_name'])."</option>";
            }
            ?>
        </select>

        <select name="shift" required>
            <option value="">⏰ Select Shift</option>
            <?php
            $s=mysqli_query($conn,"SELECT * FROM shifts");
            while($r=mysqli_fetch_assoc($s)){
                echo "<option value='".htmlspecialchars($r['id'])."'>".htmlspecialchars($r['shift_name'])."</option>";
            }
            ?>
        </select>

        <button name="allocate" class="primary">✅ Allocate</button>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h3>📊 Current Allocations</h3>
    </div>
    <table>
    <tr><th>Operator</th><th>Machine</th><th>Shift</th><th>Action</th></tr>

    <?php
    $q="SELECT a.id, o.username, m.machine_id, m.machine_name, s.shift_name
    FROM allocations a
    JOIN operators o ON a.operator_username=o.username
    JOIN machines m ON a.machine_id=m.machine_id
    JOIN shifts s ON a.shift_id=s.id";
    $res=mysqli_query($conn,$q);
    while($row=mysqli_fetch_assoc($res)){
        echo "<tr>
        <td><strong>".htmlspecialchars($row['username'])."</strong></td>
        <td>".htmlspecialchars($row['machine_id'])." - ".htmlspecialchars($row['machine_name'])."</td>
        <td>".htmlspecialchars($row['shift_name'])."</td>
        <td><a href='?delete=".htmlspecialchars($row['id'])."'>🗑️ Remove</a></td>
        </tr>";
    }
    ?>
    </table>
</div>
</div>
</body>
</html>
