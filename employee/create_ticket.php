<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SESSION['role'] != 2) {
    header("Location: ../login.php");
    exit();
}

include '../includes/db.php';
include '../includes/notification.php';


/* =========================
   CREATE TICKET
========================= */

if (isset($_POST['create_ticket'])) {

    $ticketNumber = "TKT" . time();

    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $priority = mysqli_real_escape_string($conn, $_POST['priority']);

    $createdBy = $_SESSION['user_id'];


    /* Insert Ticket */

    $sql = "INSERT INTO tickets
    (
        TicketNumber,
        Title,
        Description,
        CategoryId,
        PriorityId,
        StatusId,
        CreatedBy
    )
    VALUES
    (
        '$ticketNumber',
        '$title',
        '$description',
        '$category',
        '$priority',
        1,
        '$createdBy'
    )";


    if (mysqli_query($conn, $sql)) {

        /* Get newly created Ticket ID */

        $ticketId = mysqli_insert_id($conn);


        /* Get all active admins */

        $adminQuery = "
        SELECT ID
        FROM users
        WHERE RoleId = 1
        AND IsActive = 1
        ";

        $adminResult = mysqli_query($conn, $adminQuery);


        /* Send notification to admins */

        while ($admin = mysqli_fetch_assoc($adminResult)) {

            addNotification(
                $conn,
                $admin['ID'],
                $ticketId,
                "New ticket created by " . $_SESSION['name']
            );

        }


        $success = "Ticket created successfully!";

    }
    else {

        $error = mysqli_error($conn);

    }

}

?>


<!DOCTYPE html>

<html>

<head>

    <meta charset="UTF-8">

    <title>Create Ticket</title>

    <link rel="stylesheet" href="../assets/css/style.css">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    >

</head>


<body>


<div class="container mt-5" style="padding-bottom:80px; margin-bottom: 50px;">

    <h2>
        <i class="fa-solid fa-plus"></i>
        Create New Ticket
    </h2>

    <hr>


    <!-- SUCCESS MESSAGE -->

    <?php if (isset($success)) { ?>

        <div class="alert alert-success">

            <?php echo $success; ?>

        </div>

    <?php } ?>


    <!-- ERROR MESSAGE -->

    <?php if (isset($error)) { ?>

        <div class="alert alert-danger">

            <?php echo $error; ?>

        </div>

    <?php } ?>


    <!-- CREATE TICKET FORM -->

    <form method="POST">


        <!-- TITLE -->

        <div class="mb-3">

            <label class="form-label">
                Title
            </label>

            <input
                type="text"
                name="title"
                class="form-control"
                required
            >

        </div>


        <!-- DESCRIPTION -->

        <div class="mb-3">

            <label class="form-label">
                Description
            </label>

            <textarea
                name="description"
                class="form-control"
                rows="5"
                required
            ></textarea>

        </div>


        <!-- CATEGORY -->

        <div class="mb-3">

            <label class="form-label">
                Category
            </label>

            <select
                name="category"
                class="form-control"
                required
            >

                <option value="">
                    Select Category
                </option>

                <option value="1">
                    Hardware
                </option>

                <option value="2">
                    Software
                </option>

                <option value="3">
                    Network
                </option>

                <option value="4">
                    Email
                </option>

                <option value="5">
                    Security
                </option>

                <option value="6">
                    Other
                </option>

            </select>

        </div>


        <!-- PRIORITY -->

        <div class="mb-3">

            <label class="form-label">
                Priority
            </label>

            <select
                name="priority"
                class="form-control"
                required
            >

                <option value="">
                    Select Priority
                </option>

                <option value="1">
                    Low
                </option>

                <option value="2">
                    Medium
                </option>

                <option value="3">
                    High
                </option>

                <option value="4">
                    Critical
                </option>

            </select>

        </div>


        <!-- BUTTONS -->

        <button
            type="submit"
            name="create_ticket"
            class="btn btn-success"
        >

            <i class="fa-solid fa-plus"></i>

            Create Ticket

        </button>


        <a
            href="dashboard.php"
            class="btn btn-secondary ms-2"
        >

            <i class="fa-solid fa-arrow-left"></i>

            Back

        </a>


    </form>


</div>


</body>

</html>