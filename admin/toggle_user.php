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

    $getUser = mysqli_query($conn, "
SELECT roles.RoleName
FROM users
JOIN roles ON users.RoleId = roles.ID
WHERE users.ID = '$id'
");

$user = mysqli_fetch_assoc($getUser);

if($user['RoleName'] == 'Admin')
{
    echo "<script>
    alert('Admin account cannot be deactivated.');
    window.location='users.php';
    </script>";
    exit();
}

    $query = "UPDATE users SET IsActive='$status' WHERE ID='$id'";

    if(mysqli_query($conn,$query))
    {
        header("Location: users.php");
        exit();
    }
    else
    {
        echo "Error: ".mysqli_error($conn);
    }
}
?>