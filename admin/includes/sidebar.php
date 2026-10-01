<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<div class="sidebar">

    <div class="logo">ServiceDesk</div>

    <a href="dashboard.php" class="<?php if($currentPage=='dashboard.php') echo 'active'; ?>">
        <i class="fa-solid fa-house"></i>
        <span>Dashboard</span>
    </a>

    <a href="tickets.php" class="<?php if($currentPage=='tickets.php') echo 'active'; ?>">
        <i class="fa-solid fa-ticket"></i>
        <span>All Tickets</span>
    </a>

    <a href="users.php"
class="<?php if($currentPage=='users.php' || $currentPage=='add_user.php' || $currentPage=='edit_user.php') echo 'active'; ?>">
    <i class="fa-solid fa-users"></i>
    <span>Manage Users</span>
</a>
        

    <a href="categories.php"
class="<?php if($currentPage=='categories.php' || $currentPage=='add_category.php' || $currentPage=='edit_category.php') echo 'active'; ?>">
    <i class="fa-solid fa-layer-group"></i>
    <span>Categories</span>
</a>

    <a href="activity_logs.php" class="<?php if($currentPage=='activity_logs.php') echo 'active'; ?>">
        <i class="fa-solid fa-clock-rotate-left"></i>
        <span>Activity Logs</span>
    </a>
    <li class="<?= ($currentPage=="notifications.php") ? "active" : "" ?>">
    <a href="notifications.php">
        <i class="fa-solid fa-bell"></i>
        Notifications
    </a>
</li>

    <a href="employee_report.php" class="<?php if($currentPage=='employee_report.php') echo 'active'; ?>">
        <i class="fa-solid fa-chart-column"></i>
        <span>Employee Report</span>
    </a>

    <a href="../logout.php">
        <i class="fa-solid fa-right-from-bracket"></i>
        <span>Logout</span>
    </a>

</div>