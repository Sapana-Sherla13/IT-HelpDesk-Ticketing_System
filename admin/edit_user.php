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

// Get User ID
$id = $_GET['id'];

// Fetch User Details
$query = "SELECT * FROM users WHERE ID='$id'";
$result = mysqli_query($conn,$query);
$user = mysqli_fetch_assoc($result);

// Update User
if(isset($_POST['update_user']))
{
    $firstName = $_POST['firstname'];
    $lastName  = $_POST['lastname'];
    $email     = $_POST['email'];
    $phone     = $_POST['phone'];

    // Phone validation
    if(!preg_match('/^[0-9]{10}$/', $phone))
    {
        echo "<script>alert('Phone number must be exactly 10 digits.');</script>";
    }
    else
    {
        $update = "UPDATE users SET
                    FirstName='$firstName',
                    LastName='$lastName',
                    Email='$email',
                    Phone='$phone'
                    WHERE ID='$id'";

        if(mysqli_query($conn,$update))
        {
            echo "<script>
            alert('User Updated Successfully');
            window.location='users.php';
            </script>";
            exit();
        }
        else
        {
            echo "Error : ".mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Edit User</title>

<link rel="stylesheet" href="../assets/css/style.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">

<div class="container-fluid mt-4">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h3>Edit User</h3>

</div>

<div class="card-body">

<form method="POST">

<div class="mb-3">

<label>First Name</label>

<input
type="text"
name="firstname"
class="form-control"
value="<?php echo $user['FirstName']; ?>"
required>

</div>

<div class="mb-3">

<label>Last Name</label>

<input
type="text"
name="lastname"
class="form-control"
value="<?php echo $user['LastName']; ?>"
required>

</div>

<div class="mb-3">

<label>Email</label>

<input
type="email"
name="email"
class="form-control"
value="<?php echo $user['Email']; ?>"
required>

</div>

<div class="mb-3">

<label>Phone</label>

<input
type="tel"
name="phone"
class="form-control"
value="<?php echo $user['Phone']; ?>"
maxlength="10"
pattern="[0-9]{10}"
oninput="this.value=this.value.replace(/[^0-9]/g,'')"
required>

</div>

<button
type="submit"
name="update_user"
class="btn btn-success">

<i class="fa fa-save"></i>
Update User

</button>

<a href="users.php"
class="btn btn-secondary">

Cancel

</a>

</form>

</div>

</div>

</div>

</div>

</body>

</html>