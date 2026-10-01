<?php
session_start();
include 'includes/db.php';

if(isset($_POST['login']))
{
    $email = $_POST['email'];
    $password = $_POST['password'];

    // THIS QUERY GOES HERE
    $query = "SELECT * FROM users
              WHERE Email='$email'
              AND IsActive=1";

    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) == 1)
    {
        $user = mysqli_fetch_assoc($result);

        if($password == $user['Password'])
        {
            $_SESSION['user_id'] = $user['ID'];
            $_SESSION['name'] = $user['FirstName'];
            $_SESSION['role'] = $user['RoleId'];

            if($user['RoleId'] == 1)
            {
                header("Location: admin/dashboard.php");
            }
            else
            {
                header("Location: employee/dashboard.php");
            }

            exit();
        }
        else
        {
            $error = "Invalid Email or Password!";
        }
    }
    else
    {
        $error = "Invalid Email or Password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<!-- Custom CSS -->
<link rel="stylesheet" href="assets/css/login.css">

</head>

<body>

<div class="login-container">

    <div class="login-card">

        <div class="logo">
            <i class="fa-solid fa-headset"></i>
            <h2>ServiceDesk</h2>
        </div>

        <h3>Welcome back</h3>

        <p class="subtitle">
            Please enter your details to sign in.
        </p>

        <form method="POST">

            <!-- Email -->
            <div class="input-group">
                <i class="fa-solid fa-envelope"></i>

                <input
                    type="email"
                    name="email"
                    placeholder="Email address"
                    required>
            </div>

            <!-- Password -->
            <div class="input-group">
                <i class="fa-solid fa-lock"></i>

                <input
                    type="password"
                    name="password"
                    placeholder="Password"
                    required>
            </div>

            <button
                type="submit"
                name="login"
                class="login-btn">

                Login

            </button>

            <div class="text-center mt-3">
                <a href="forgot_password.php"
                   class="text-decoration-none">

                    Forgot Password?

                </a>
            </div>

        </form>

    </div>

</div>

</body>
</html>