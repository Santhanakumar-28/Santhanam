<?php
session_start();
include "db.php";

$username = $_POST['username'];
$password = $_POST['password'];

/* ADMIN LOGIN */
if ($username === "admin" && $password === "admin123") {
    $_SESSION['admin'] = true;
    header("Location: admin_dashboard.php");
    exit();
}

/* OPERATOR LOGIN */
$sql = "SELECT * FROM operators WHERE username='$username' AND password='$password'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {
    $_SESSION['operator'] = $username;
    header("Location: operator_page.php");
} else {
    echo "Invalid login";
}
?>
