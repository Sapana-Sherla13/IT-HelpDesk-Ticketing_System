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

include '../includes/db.php';

$userId = $_SESSION['user_id'];


/* Mark notification as read */

if(isset($_GET['read']))
{
    $notificationId = (int)$_GET['read'];

    mysqli_query($conn,"
        UPDATE notifications
        SET IsRead = 1
        WHERE ID='$notificationId'
        AND UserId='$userId'
    ");

    header("Location: notifications.php");
    exit();
}


/* Get admin notifications */

$query = "
SELECT
    notifications.ID,
    notifications.TicketId,
    notifications.Message,
    notifications.IsRead,
    notifications.CreatedDate,
    tickets.TicketNumber,
    tickets.Title

FROM notifications

LEFT JOIN tickets
ON notifications.TicketId = tickets.ID

WHERE notifications.UserId='$userId'

ORDER BY notifications.ID DESC
";

$result = mysqli_query($conn,$query);

?>

<!DOCTYPE html>
<html>

<head>

<title>Notifications</title>

<link rel="stylesheet" href="../assets/css/style.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">

<div class="container-fluid mt-4">

<div class="topbar">

    <h2>Notifications</h2>

    <div class="profile">

        <i class="fa-solid fa-circle-user fa-2x"></i>

        <span>
            <?php echo $_SESSION['name']; ?>
        </span>

    </div>

</div>


<div class="card shadow-sm border-0">

<div class="card-body">

<h4 class="mb-4">

<i class="fa-solid fa-bell text-primary"></i>

My Notifications

</h4>


<?php

if(mysqli_num_rows($result) > 0)
{

    while($row = mysqli_fetch_assoc($result))
    {

?>

<div class="notification-item
<?php echo ($row['IsRead'] == 0) ? 'bg-light' : ''; ?>">

<div class="d-flex justify-content-between align-items-center">

<div>

    <h6 class="fw-bold mb-1">

        <?php echo htmlspecialchars($row['Message']); ?>

    </h6>

    <?php if($row['TicketNumber'] != NULL) { ?>

        <p class="mb-1 text-muted">

            <i class="fa-solid fa-ticket"></i>

            <?php echo htmlspecialchars($row['TicketNumber']); ?>

            -

            <?php echo htmlspecialchars($row['Title']); ?>

        </p>

    <?php } ?>

    <small class="text-muted">

        <?php echo date("d M Y H:i", strtotime($row['CreatedDate'])); ?>

    </small>

</div>


<div>

<?php if($row['TicketId'] != NULL) { ?>

<a
href="ticket_details.php?id=<?php echo $row['TicketId']; ?>&read=<?php echo $row['ID']; ?>"
class="btn btn-primary btn-sm">

<i class="fa-solid fa-eye"></i>

View Ticket

</a>

<?php } else { ?>

<a
href="notifications.php?read=<?php echo $row['ID']; ?>"
class="btn btn-secondary btn-sm">

Mark as Read

</a>

<?php } ?>

</div>

</div>

</div>

<hr>

<?php

    }

}
else
{

?>

<div class="text-center py-5">

<i class="fa-solid fa-bell-slash fa-3x text-secondary mb-3"></i>

<h5 class="text-secondary">

No Notifications

</h5>

<p class="text-muted">

You don't have any notifications yet.

</p>

</div>

<?php

}

?>

</div>

</div>

</div>

</div>

</body>

</html>