<?php
$host="localhost";
$use ="root";
$pass ="";
$db="bottleneck_db";
$conn = new mysqli($host, $use, $pass, $db);

if(!$conn){
    die("Connection failed: ". mysqli_connect_error());
}
echo "Connected successfully";
?>