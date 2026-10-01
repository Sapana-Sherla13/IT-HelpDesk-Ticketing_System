<?php
session_start();
if(!isset($_SESSION['user_id']))
{
    header("Location: ../login.php");
    exit();
}

if($_SESSION['role'] != 1)
{
    header("Location: ../login.php");
    exit();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../login.php");
    exit();
}
?>
<?php


include '../includes/db.php';

if(!isset($_SESSION['user_id']))
{
    header("Location: ../login.php");
    exit();
}

if(isset($_GET['id']) && isset($_GET['status']))
{
    $id = (int)$_GET['id'];
    $status = (int)$_GET['status'];

    $query = "UPDATE categories
              SET IsActive='$status'
              WHERE ID='$id'";

    if(mysqli_query($conn,$query))
    {
        header("Location: categories.php");
        exit();
    }
    else
    {
        echo "Error: ".mysqli_error($conn);
    }
}
?>