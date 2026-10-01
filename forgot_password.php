<?php
session_start();
include 'includes/db.php';

$message = "";
$error = "";

if (isset($_POST['check_email'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $query = "
        SELECT ID
        FROM users
        WHERE Email='$email'
        AND IsActive=1
    ";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        // Store user ID temporarily
        $_SESSION['reset_user_id'] = $user['ID'];

        // Go to reset password page
        header("Location: reset_password.php");
        exit();

    } else {

        $error = "Email address not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Forgot Password</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <link rel="stylesheet" href="assets/css/login.css">

</head>

<body>

<div class="login-container">

    <div class="login-card">

        <div class="logo">

            <i class="fa-solid fa-headset"></i>

            <h2>ServiceDesk</h2>

        </div>

        <h3>Forgot Password?</h3>

        <p class="subtitle">
            Enter your registered email address.
        </p>


        <?php if ($error != "") { ?>

            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>

        <?php } ?>


        <form method="POST">

            <div class="input-group">

                <i class="fa-solid fa-envelope"></i>

                <input
                    type="email"
                    name="email"
                    placeholder="Email address"
                    required>

            </div>


            <button
                type="submit"
                name="check_email"
                class="login-btn">

                Continue

            </button>

        </form>


        <div class="text-center mt-3">

            <a href="login.php"
               class="text-decoration-none">

                <i class="fa-solid fa-arrow-left"></i>
                Back to Login

            </a>

        </div>

    </div>

</div>

</body>

</html>