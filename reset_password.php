<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['reset_user_id'])) {
    header("Location: forgot_password.php");
    exit();
}

$userId = $_SESSION['reset_user_id'];

$success = "";
$error = "";

if (isset($_POST['reset_password'])) {

    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($password != $confirmPassword) {

        $error = "Passwords do not match!";

    } elseif (strlen($password) < 6) {

        $error = "Password must be at least 6 characters!";

    } else {

        $password = mysqli_real_escape_string($conn, $password);

        $query = "
            UPDATE users
            SET Password='$password'
            WHERE ID='$userId'
        ";

        if (mysqli_query($conn, $query)) {

            unset($_SESSION['reset_user_id']);

            $success = "Password changed successfully!";

        } else {

            $error = mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Reset Password</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <link rel="stylesheet"
          href="assets/css/login.css">

</head>

<body>

<div class="login-container">

    <div class="login-card">

        <div class="logo">

            <i class="fa-solid fa-headset"></i>

            <h2>ServiceDesk</h2>

        </div>

        <h3>Reset Password</h3>

        <p class="subtitle">
            Enter your new password.
        </p>


        <?php if ($error != "") { ?>

            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>

        <?php } ?>


        <?php if ($success != "") { ?>

            <div class="alert alert-success">
                <?php echo $success; ?>
            </div>

            <div class="text-center mt-3">

                <a href="login.php"
                   class="btn btn-primary">

                    Go to Login

                </a>

            </div>

        <?php } else { ?>


        <form method="POST">

            <div class="input-group">

                <i class="fa-solid fa-lock"></i>

                <input
                    type="password"
                    name="password"
                    placeholder="New Password"
                    required>

            </div>


            <div class="input-group">

                <i class="fa-solid fa-lock"></i>

                <input
                    type="password"
                    name="confirm_password"
                    placeholder="Confirm New Password"
                    required>

            </div>


            <button
                type="submit"
                name="reset_password"
                class="login-btn">

                Reset Password

            </button>

        </form>


        <?php } ?>

    </div>

</div>

</body>

</html>