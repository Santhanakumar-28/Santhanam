<?php
session_start();
include "db.php";

if (!isset($_SESSION['operator'])) {
    header("Location: login.html");
    exit();
}

$operator = $_SESSION['operator'];

/* HANDLE ON / OFF ACTION */
if (isset($_POST['status'])) {

    $machine_id = $_POST['machine_id'];
    $shift_id   = $_POST['shift_id'];
    $status     = $_POST['status'];
    $reason     = $_POST['reason'] ?? '';
    $production = $_POST['production'] ?? 0;
    $delaySeconds = 0;

    /* GET PREVIOUS LOG */
    $prev = mysqli_query($conn, "
        SELECT status, reason, log_time
        FROM machine_logs
        WHERE operator_username='$operator'
        AND machine_id='$machine_id'
        AND shift_id='$shift_id'
        ORDER BY log_time DESC
        LIMIT 1
    ");

    $prevRow = mysqli_fetch_assoc($prev);

    /* CALCULATE DELAY (OFF → ON) */
    if (
        $prevRow &&
        $prevRow['status'] == 'OFF' &&
        $status == 'ON' &&
        strtoupper($prevRow['reason']) != 'END'
    ) {
        $start = strtotime($prevRow['log_time']);

        // ✅ USE MYSQL TIME (FIX NEGATIVE ISSUE)
        $nowQ = mysqli_query($conn, "SELECT NOW() AS now_time");
        $nowR = mysqli_fetch_assoc($nowQ);
        $end  = strtotime($nowR['now_time']);

        $delaySeconds = $end - $start;

        // SAFETY
        if ($delaySeconds < 0) {
            $delaySeconds = 0;
        }
    }

    /* VALIDATION */
    if ($status == "OFF" && strtoupper($reason) == "END" && $production == 0) {
        echo "<script>alert('Production is mandatory when END');</script>";
    } else {

        $stmt = $conn->prepare(
            "INSERT INTO machine_logs
            (operator_username, machine_id, shift_id, status, reason, production, delay_seconds)
            VALUES (?,?,?,?,?,?,?)"
        );

        $stmt->bind_param(
            "ssissii",
            $operator,
            $machine_id,
            $shift_id,
            $status,
            $reason,
            $production,
            $delaySeconds
        );

        $stmt->execute();
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Operator Dashboard - SRM</title>
<link rel="stylesheet" href="style.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="content" style="margin-left: 0;">
<h2>👋 Welcome, <?php echo htmlspecialchars($operator); ?>!</h2>

<div class="card">
    <div class="card-header">
        <h3>🔧 Assigned Machines & Shifts</h3>
    </div>

<table>
<tr>
    <th>Machine</th>
    <th>Shift</th>
    <th>Current Status</th>
    <th>Action</th>
</tr>

<?php
$assign = mysqli_query($conn,"
SELECT a.machine_id, m.machine_name, a.shift_id, s.shift_name
FROM allocations a
JOIN machines m ON a.machine_id = m.machine_id
JOIN shifts s ON a.shift_id = s.id
WHERE a.operator_username = '$operator'
");

while ($row = mysqli_fetch_assoc($assign)) {

    $mid = $row['machine_id'];
    $sid = $row['shift_id'];

    $last = mysqli_query($conn,"
    SELECT status FROM machine_logs
    WHERE operator_username='$operator'
    AND machine_id='$mid'
    AND shift_id=$sid
    ORDER BY log_time DESC LIMIT 1
    ");
    $ls = mysqli_fetch_assoc($last);
    $status = $ls['status'] ?? "OFF";
?>
<tr>
<td><?php echo $mid." - ".$row['machine_name']; ?></td>
<td><?php echo $row['shift_name']; ?></td>
<td><span class="status <?php echo $status == 'ON' ? 'status-on' : 'status-off'; ?>"><?php echo htmlspecialchars($status); ?></span></td>
<td>
    <div style="display: flex; gap: 10px; align-items: center; justify-content: center; flex-wrap: wrap;">
        <!-- ON -->
        <form method="post" style="display:inline; margin: 0;">
            <input type="hidden" name="machine_id" value="<?php echo htmlspecialchars($mid); ?>">
            <input type="hidden" name="shift_id" value="<?php echo htmlspecialchars($sid); ?>">
            <input type="hidden" name="status" value="ON">
            <button type="submit" class="btn-on">✅ ON</button>
        </form>

        <!-- OFF -->
        <form method="post" style="display:inline-flex; gap: 8px; align-items: center; margin: 0;">
            <input type="hidden" name="machine_id" value="<?php echo htmlspecialchars($mid); ?>">
            <input type="hidden" name="shift_id" value="<?php echo htmlspecialchars($sid); ?>">
            <input type="hidden" name="status" value="OFF">
            <input type="text" name="reason" placeholder="Reason / END" required style="min-width: 120px;">
            <input type="number" name="production" placeholder="Production" style="min-width: 100px;">
            <button type="submit" class="btn-off">❌ OFF</button>
        </form>
    </div>
</td>
</tr>
<?php } ?>
</table>
</div>
<?php
echo "<div class='card'>";
echo "<div class='card-header'><h3>⏱️ Delay Time Summary (Minutes)</h3></div>";

echo "<table class='log-table'>
<tr>
    <th>Machine</th>
    <th>Shift</th>
    <th>Total Delay (Minutes)</th>
</tr>";

$delayQuery = mysqli_query($conn, "
SELECT l.machine_id, m.machine_name, l.shift_id, s.shift_name,
       l.status, l.reason, l.log_time
FROM machine_logs l
JOIN machines m ON l.machine_id = m.machine_id
JOIN shifts s ON l.shift_id = s.id
WHERE l.operator_username = '$operator'
ORDER BY l.machine_id, l.shift_id, l.log_time
");

$prev = [];
$delay = [];

while ($row = mysqli_fetch_assoc($delayQuery)) {

    $key = $row['machine_id'] . '_' . $row['shift_id'];

    if (!isset($prev[$key])) {
        $prev[$key] = $row;
        continue;
    }

    $prevRow = $prev[$key];

    if (
        $prevRow['status'] == 'OFF' &&
        $row['status'] == 'ON' &&
        strtoupper($prevRow['reason']) != 'END'
    ) {
        $start = strtotime($prevRow['log_time']);
        $end   = strtotime($row['log_time']);

        if (!isset($delay[$key])) {
            $delay[$key] = 0;
        }

        $delay[$key] += ($end - $start);
    }

    $prev[$key] = $row;
}

foreach ($delay as $key => $seconds) {

    list($machine_id, $shift_id) = explode('_', $key);

    $minutes = round($seconds / 60, 2);

    echo "<tr>
        <td>$machine_id</td>
        <td>$shift_id</td>
        <td><strong>{$minutes} min</strong></td>
    </tr>";
}

echo "</table>";
echo "</div>";
?>

<div class="card">
    <div class="card-header">
        <h3>📋 Machine ON / OFF Logs (Real-Time)</h3>
    </div>

<table class="log-table">
<tr>
<th>Machine</th>
<th>Shift</th>
<th>Status</th>
<th>Reason</th>
<th>Production</th>
<th>Time</th>
</tr>

<?php
$logs = mysqli_query($conn,"
SELECT m.machine_name, l.machine_id, s.shift_name,
l.status, l.reason, l.production, l.log_time
FROM machine_logs l
JOIN machines m ON l.machine_id = m.machine_id
JOIN shifts s ON l.shift_id = s.id
WHERE l.operator_username='$operator'
ORDER BY l.log_time DESC
");

while ($l = mysqli_fetch_assoc($logs)) {
    $statusClass = $l['status'] == 'ON' ? 'status-on' : 'status-off';
    echo "<tr>
    <td><strong>".htmlspecialchars($l['machine_id'])."</strong> - ".htmlspecialchars($l['machine_name'])."</td>
    <td>".htmlspecialchars($l['shift_name'])."</td>
    <td><span class='status ".$statusClass."'>".htmlspecialchars($l['status'])."</span></td>
    <td>".htmlspecialchars($l['reason'] ?? '-')."</td>
    <td><strong>".htmlspecialchars($l['production'])."</strong></td>
    <td>".htmlspecialchars($l['log_time'])."</td>
    </tr>";
}
?>
<?php
echo "<div class='card'>";
echo "<div class='card-header'><h3>⏱️ Delay Time Summary</h3></div>";

$delayQuery = mysqli_query($conn, "
SELECT machine_id, shift_id, status, reason, log_time
FROM machine_logs
WHERE operator_username = '$operator'
ORDER BY machine_id, shift_id, log_time
");

$prev = [];
$delay = [];

while ($row = mysqli_fetch_assoc($delayQuery)) {

    $key = $row['machine_id'] . '_' . $row['shift_id'];

    if (!isset($prev[$key])) {
        $prev[$key] = $row;
        continue;
    }

    $prevRow = $prev[$key];

    // OFF → ON means delay ended
    if (
        $prevRow['status'] == 'OFF' &&
        $row['status'] == 'ON' &&
        strtoupper($prevRow['reason']) != 'END'
    ) {
        $start = strtotime($prevRow['log_time']);
        $end   = strtotime($row['log_time']);
        $diff  = $end - $start;

        if (!isset($delay[$key])) {
            $delay[$key] = 0;
        }
        $delay[$key] += $diff;
    }

    $prev[$key] = $row;
}
?>
