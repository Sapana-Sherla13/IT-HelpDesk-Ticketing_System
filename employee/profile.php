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

$query = "
SELECT
FirstName,
LastName,
Email,
Phone
FROM users
WHERE ID='$userId'
";

$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// 👇 Paste the update code here
if(isset($_POST['update_profile']))
{
    $firstName = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastName  = mysqli_real_escape_string($conn, $_POST['lastname']);
    $email     = mysqli_real_escape_string($conn, $_POST['email']);
    $phone     = mysqli_real_escape_string($conn, $_POST['phone']);

    $updateQuery = "
    UPDATE users
    SET
        FirstName='$firstName',
        LastName='$lastName',
        Email='$email',
        Phone='$phone'
    WHERE ID='$userId'
    ";

    if(mysqli_query($conn, $updateQuery))
{
    // Update session name
    $_SESSION['name'] = $firstName;

    // Store success message
    $_SESSION['success_message'] = "Profile updated successfully!";

    // Redirect to dashboard
    header("Location: dashboard.php");
    exit();
}
else
{
    $message = "Error updating profile.";
}
}

?>

<!DOCTYPE html>
<html>

<head>

<title>My Profile</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h3>

<i class="fa-solid fa-user"></i>

My Profile

</h3>

</div>

<div class="card-body">
    <?php
if(isset($message))
{
?>
<div class="alert alert-success">
    <?php echo $message; ?>
</div>
<?php
}
?>
<a href="dashboard.php" class="btn btn-secondary ms-2">
    Back
</a>

<form method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<label>First Name</label>

<input
type="text"
name="firstname"
class="form-control"
value="<?php echo $user['FirstName']; ?>">

</div>

<div class="col-md-6 mb-3">

<label>Last Name</label>

<input
type="text"
name="lastname"
class="form-control"
value="<?php echo $user['LastName']; ?>">

</div>

<div class="col-md-6 mb-3">

<label>Email</label>

<input
type="email"
name="email"
class="form-control"
value="<?php echo $user['Email']; ?>">

</div>

<div class="col-md-6 mb-3">

<label>Phone</label>

<input
type="text"
name="phone"
class="form-control"
value="<?php echo $user['Phone']; ?>">

</div>

<button
type="submit"
name="update_profile"
class="btn btn-success">

Update Profile

</button>

</form>

</div>

</div>

</div>

</body>

</html>