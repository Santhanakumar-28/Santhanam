
<?php
$host = "bbhrlchiqirqx1uljccv-mysql.services.clever-cloud.com"; // Clever HOST
$user = "u43ksuvy0omy8zdq";
$pass = "x1WQV17PsfbHMWbAtpSJ";
$db   = "ybbhrlchiqirqx1uljccv";
$port = 3306;

$conn = mysqli_connect($host, $user, $pass, $db, $port);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
