<?php
session_start();
if (!isset($_SESSION['admin'])) header("Location: login.html");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bottleneck Analysis - SRM</title>
<link rel="stylesheet" href="style.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<?php include "sidebar.php"; ?>

<div class="content">
<h2>📊 Bottleneck Analysis</h2>

<div class="card">
   <iframe title="SRM" width="1140" height="541.25" src="https://app.powerbi.com/reportEmbed?reportId=360e3cc7-969a-4772-97fc-1d9bffadf2c7&autoAuth=true&ctid=1f16e18a-e2cb-470a-b778-44cc219b8a38" frameborder="0" allowFullScreen="true"></iframe>

</div>

</body>
</html>
