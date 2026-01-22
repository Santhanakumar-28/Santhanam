<div class="sidebar">
<h3>⚙️ Admin Panel</h3>
<?php
$current_page = isset($_SERVER['PHP_SELF']) ? basename($_SERVER['PHP_SELF']) : '';
?>
<a href="bottleneck_analysis.php" class="<?php echo $current_page == 'bottleneck_analysis.php' || $current_page == 'admin_dashboard.php' ? 'active' : ''; ?>">📊 Bottleneck Analysis</a>
<a href="add_operator.php" class="<?php echo $current_page == 'add_operator.php' ? 'active' : ''; ?>">👥 Add Operator</a>
<a href="add_machine.php" class="<?php echo $current_page == 'add_machine.php' ? 'active' : ''; ?>">🔧 Add Machine</a>
<a href="add_shift.php" class="<?php echo $current_page == 'add_shift.php' ? 'active' : ''; ?>">⏰ Add Shift</a>
<a href="manage_shift.php" class="<?php echo $current_page == 'manage_shift.php' ? 'active' : ''; ?>">📋 Manage Shifts</a>
<a href="logout.php">🚪 Logout</a>
</div>



