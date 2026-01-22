<?php
session_start();

include "db.php";


if (!isset($_SESSION['admin'])) {
    header("Location: login.html");
}
?>
<?php
// Total Machines
$q1 = mysqli_query($conn, "SELECT COUNT(*) AS total_machines FROM machines");
$totalMachines = mysqli_fetch_assoc($q1)['total_machines'];

// Active Operators
$q2 = mysqli_query($conn, "SELECT COUNT(*) AS active_operators FROM operators");
$activeOperators = mysqli_fetch_assoc($q2)['active_operators'];

// Running Shifts
$q3 = mysqli_query($conn, "SELECT COUNT(*) AS running_shifts FROM shifts");
$runningShifts = mysqli_fetch_assoc($q3)['running_shifts'];

// Production Status (Total production today)
$q4 = mysqli_query($conn, "SELECT SUM(production) AS total_production FROM machine_logs");
$productionStatus = mysqli_fetch_assoc($q4)['total_production'];
if ($productionStatus == NULL) {
    $productionStatus = 0;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SRM</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<?php include "sidebar.php"; ?>


<div class="content">
    <h2>📊 Dashboard Overview</h2>
    
    <div class="dashboard-grid">
    <div class="stat-card">
        <h4>Total Machines</h4>
        <div class="value"><?php echo $totalMachines; ?></div>
    </div>

    <div class="stat-card">
        <h4>Active Operators</h4>
        <div class="value"><?php echo $activeOperators; ?></div>
    </div>

    <div class="stat-card">
        <h4>Running Shifts</h4>
        <div class="value"><?php echo $runningShifts; ?></div>
    </div>

    <div class="stat-card">
        <h4>Production Status</h4>
        <div class="value"><?php echo $productionStatus; ?></div>
    </div>
</div>

    
    <div class="card">
        <div class="card-header">
            <h3>🚀 Quick Actions</h3>
        </div>
        <p style="color: #64748b; font-size: 16px;">Welcome to your admin dashboard. Use the sidebar to navigate to different sections.</p>
    </div>
</div>

</body>
</html>
