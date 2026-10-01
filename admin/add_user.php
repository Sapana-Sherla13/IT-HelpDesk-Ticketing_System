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


if(isset($_POST['add_user']))
{

    $firstName = $_POST['firstname'];
    $lastName = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    if (!preg_match('/^[0-9]{10}$/', $phone)) {
    echo "<script>
    alert('Phone number must contain exactly 10 digits.');
    window.history.back();
    </script>";
    exit();
}


    $password = $_POST['password'];
    $role = $_POST['role'];


    $sql = "INSERT INTO users
    (FirstName, LastName, Email, Phone, Password, RoleId, IsActive)
    VALUES
    ('$firstName','$lastName','$email','$phone','$password','$role',1)";


    if(mysqli_query($conn,$sql))
    {
        echo "<script>
        alert('User Added Successfully');
        window.location='users.php';
        </script>";
        exit();
    }
    else
    {
    echo "Error: ".mysqli_error($conn);
    }

}

?>


<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="../assets/css/style.css">
<title>Add User</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>


<body>


<div class="main-content">
    <div class="container-fluid mt-4">


<h2>Add User</h2>


<form method="POST">


<div class="mb-3">
<label>First Name</label>
<input type="text" name="firstname" class="form-control" required>
</div>


<div class="mb-3">
<label>Last Name</label>
<input type="text" name="lastname" class="form-control" required>
</div>


<div class="mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control" required>
</div>


<div class="mb-3">
<label>Phone</label>
<input type="tel"
       name="phone"
       class="form-control"
       pattern="[0-9]{10}"
       maxlength="10"
       minlength="10"
       inputmode="numeric"
       oninput="this.value=this.value.replace(/[^0-9]/g,'')"
       placeholder="Enter 10-digit phone number"
       required>
</div>


<div class="mb-3">
<label>Password</label>
<input type="password" name="password" class="form-control" required>
</div>


<div class="mb-3">

<label>Role</label>

<input type="text" class="form-control" value="Employee" readonly>

<input type="hidden" name="role" value="2">

</div>


<button type="submit" name="add_user" class="btn btn-success">
Add User
</button>


</form>


</div>


</body>
</html>