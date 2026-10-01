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

if(!isset($_GET['id']))
{
    die("Ticket not found.");
}

$ticketId = $_GET['id'];
// Add Comment

// Add Comment

if(isset($_POST['add_comment']))
{
    $comment = $_POST['comment'];
    $userId = $_SESSION['user_id'];

    $sql = "INSERT INTO ticketcomments
    (TicketId, UserId, Comment)
    VALUES
    ('$ticketId','$userId','$comment')";

    mysqli_query($conn,$sql);

    mysqli_query($conn,"
    INSERT INTO activitylogs
    (TicketId, UserId, Action)
    VALUES
    ('$ticketId', '$userId', 'Added a comment')
    ");
}

if(isset($_POST['update']))
{
    $status = $_POST['status'];

    mysqli_query($conn,
    "UPDATE tickets
     SET StatusId='$status'
     WHERE ID='$ticketId'");

     $userId = $_SESSION['user_id'];

$statusName = "";

if($status == 2)
{
    $statusName = "Assigned";
}
elseif($status == 3)
{
    $statusName = "In Progress";
}
elseif($status == 4)
{
    $statusName = "Resolved";
}
elseif($status == 5)
{
    $statusName = "Closed";
}

mysqli_query($conn,"
INSERT INTO activitylogs
(TicketId, UserId, Action)
VALUES
('$ticketId', '$userId', 'Status changed to $statusName')
");

    echo "<script>
    alert('Status Updated Successfully!');
    window.location='dashboard.php';
    </script>";

    exit();
}

$query = mysqli_query($conn,

"SELECT
tickets.*,
statuses.StatusName,
priorities.PriorityName,
categories.CategoryName

FROM tickets

LEFT JOIN statuses
ON tickets.StatusId=statuses.ID

LEFT JOIN priorities
ON tickets.PriorityId=priorities.ID

LEFT JOIN categories
ON tickets.CategoryId=categories.ID

WHERE tickets.ID='$ticketId'");

$ticket=mysqli_fetch_assoc($query);
$comments = mysqli_query($conn, "
SELECT
ticketcomments.Comment,
ticketcomments.CreatedDate,
users.FirstName

FROM ticketcomments

LEFT JOIN users
ON ticketcomments.UserId = users.ID

WHERE ticketcomments.TicketId='$ticketId'

ORDER BY ticketcomments.ID DESC
");

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="../assets/css/style.css">
<title>Ticket Details</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<h2>Ticket Details</h2>

<hr>

<p><b>Ticket Number:</b> <?php echo $ticket['TicketNumber']; ?></p>

<p><b>Title:</b> <?php echo $ticket['Title']; ?></p>

<p><b>Description:</b> <?php echo $ticket['Description']; ?></p>

<p><b>Category:</b> <?php echo $ticket['CategoryName']; ?></p>

<p><b>Priority:</b> <?php echo $ticket['PriorityName']; ?></p>

<form method="POST">

<div class="mb-3">

<label>Status</label>

<select name="status" class="form-control">

<option value="2">Assigned</option>
<option value="3">In Progress</option>
<option value="4">Resolved</option>
<option value="5">Closed</option>

</select>

</div>

<button class="btn btn-success"
name="update">

Update Status

</button>

</form>
<hr>

<h4>Comments</h4>

<?php
while($row = mysqli_fetch_assoc($comments))
{
?>

<div class="card mb-3">
    <div class="card-body">

        <b><?php echo $row['FirstName']; ?></b><br>

        <?php echo $row['Comment']; ?>

        <br><br>

        <small class="text-muted">
            <?php echo $row['CreatedDate']; ?>
        </small>

    </div>
</div>

<?php
}
?>

<hr>

<h4>Add Comment</h4>

<form method="POST">

<div class="mb-3">
    <textarea
        name="comment"
        class="form-control"
        rows="4"
        required></textarea>
</div>

<button
type="submit"
name="add_comment"
class="btn btn-primary">

Add Comment

</button>

</form>
</div>

</body>
</html>