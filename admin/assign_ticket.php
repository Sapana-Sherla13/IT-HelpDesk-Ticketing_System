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
include '../includes/notification.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Ticket ID not found.");
}

$ticketId = $_GET['id'];

if (isset($_POST['assign'])) {

    $employee = $_POST['employee'];

    $sql = "UPDATE tickets
            SET AssignedTo='$employee',
                StatusId=2
            WHERE ID='$ticketId'";

    $userId = $_SESSION['user_id'];

    mysqli_query($conn,"
    INSERT INTO activitylogs
    (TicketId, UserId, Action)
    VALUES
    ('$ticketId', '$userId', 'Assigned ticket to employee')
    ");

    if (mysqli_query($conn, $sql))
{
    // Send notification to assigned employee
    addNotification(
        $conn,
        $employee,
        $ticketId,
        "A new ticket has been assigned to you."
    );

    echo "<script>
            alert('Ticket Assigned Successfully!');
            window.location='tickets.php';
          </script>";
    exit();
}
else
{
    echo mysqli_error($conn);
}
}

$employees = mysqli_query($conn, "SELECT ID, FirstName, LastName
                                  FROM users
                                  WHERE RoleId=2 AND IsActive=1");
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="../assets/css/style.css">
    <title>Assign Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="main-content">
    <div class="container-fluid mt-4">

    <h2>Assign Ticket</h2>

    <form method="POST">

        <div class="mb-3">
            <label>Select Employee</label>

            <select name="employee" class="form-control" required>

                <option value="">Select Employee</option>

                <?php
                while($row = mysqli_fetch_assoc($employees))
                {
                ?>

                <option value="<?php echo $row['ID']; ?>">
                    <?php echo $row['FirstName']." ".$row['LastName']; ?>
                </option>

                <?php
                }
                ?>

            </select>

        </div>

        <button type="submit" name="assign" class="btn btn-success">
            Assign Ticket
        </button>

    </form>

</div>

</body>
</html>