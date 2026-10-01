<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">

    <div class="sidebar-logo">
        <i class="fa-solid fa-headset"></i>
        <span>IT HelpDesk</span>
    </div>

    <ul class="sidebar-menu">

        <li class="<?= ($currentPage=="dashboard.php") ? "active" : "" ?>">
            <a href="../employee/dashboard.php">
                <i class="fa-solid fa-house"></i>
                Dashboard
            </a>
        </li>

        <li class="<?= ($currentPage=="create_ticket.php") ? "active" : "" ?>">
            <a href="../employee/create_ticket.php">
                <i class="fa-solid fa-plus"></i>
                Create Ticket
            </a>
        </li>

        <li class="<?= ($currentPage=="my_tickets.php") ? "active" : "" ?>">
            <a href="../employee/my_tickets.php">
                <i class="fa-solid fa-ticket"></i>
                My Tickets
            </a>
        </li>

        <li class="<?= ($currentPage=="my_assigned_tickets.php") ? "active" : "" ?>">
            <a href="../employee/my_assigned_tickets.php">
                <i class="fa-solid fa-clipboard-check"></i>
                My Assigned Tickets
            </a>
        </li>

        <li class="<?= ($currentPage=="notifications.php") ? "active" : "" ?>">
    <a href="../employee/notifications.php">
        <i class="fa-solid fa-bell"></i>
        Notifications
    </a>
</li>

        <li class="<?= ($currentPage=="profile.php") ? "active" : "" ?>">
            <a href="../employee/profile.php">
                <i class="fa-solid fa-user"></i>
                My Profile
            </a>
        </li>

        <li class="<?= ($currentPage=="change_password.php") ? "active" : "" ?>">
            <a href="../employee/change_password.php">
                <i class="fa-solid fa-key"></i>
                Change Password
            </a>
        </li>

        <li>
            <a href="../logout.php">
                <i class="fa-solid fa-right-from-bracket"></i>
                Logout
            </a>
        </li>

    </ul>

</div>