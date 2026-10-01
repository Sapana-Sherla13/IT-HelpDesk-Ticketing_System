<?php
session_start();

if (isset($_SESSION['user_id'])) {

    if ($_SESSION['role'] == 'Admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: employee/dashboard.php");
    }

    exit();
}

header("Location: login.php");
exit();
?>