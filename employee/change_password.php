<?php
session_start();
if(!isset($_SESSION['user_id']))
{
    header("Location: ../login.php");
    exit();
}

if($_SESSION['role'] != 2)
{
    header("Location: ../login.php");
    exit();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
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

$userId = $_SESSION['user_id'];
if(isset($_POST['change_password']))
{
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Get current password from database
    $query = "SELECT Password FROM users WHERE ID='$userId'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if($user['Password'] != $currentPassword)
    {
        $message = "Current password is incorrect.";
        $alert = "danger";
    }
    elseif($newPassword != $confirmPassword)
    {
        $message = "New password and Confirm password do not match.";
        $alert = "warning";
    }
    else
    {
        $updateQuery = "
        UPDATE users
        SET Password='$newPassword'
        WHERE ID='$userId'
        ";

        if(mysqli_query($conn, $updateQuery))
        {
            $message = "Password changed successfully.";
            $alert = "success";
        }
        else
        {
            $message = "Something went wrong.";
            $alert = "danger";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Change Password</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h3>

<i class="fa-solid fa-lock"></i>

Change Password

</h3>

</div>

<div class="card-body">
    <?php
if(isset($message))
{
?>
<div class="alert alert-<?php echo $alert; ?>">
    <?php echo $message; ?>
</div>
<?php
}
?>

<form method="POST">

<div class="mb-3">

<label>Current Password</label>

<input
type="password"
name="current_password"
class="form-control"
required>

</div>

<div class="mb-3">

<label>New Password</label>

<input
type="password"
name="new_password"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Confirm Password</label>

<input
type="password"
name="confirm_password"
class="form-control"
required>

</div>

<button
type="submit"
name="change_password"
class="btn btn-success">

Change Password

</button>

<a href="dashboard.php" class="btn btn-secondary">

Back

</a>

</form>

</div>

</div>

</div>

</body>

</html>