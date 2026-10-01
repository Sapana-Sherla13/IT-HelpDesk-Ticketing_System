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

$query = "
SELECT
activitylogs.Action,
activitylogs.CreatedDate,
users.FirstName,
tickets.TicketNumber

FROM activitylogs

LEFT JOIN users
ON activitylogs.UserId = users.ID

LEFT JOIN tickets
ON activitylogs.TicketId = tickets.ID

ORDER BY activitylogs.CreatedDate DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="../assets/css/style.css">
<title>Activity Logs</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid mt-4">

<h2>Activity Logs</h2>

<hr>

<table class="table table-bordered table-hover">

<thead class="table-dark">


    <thead class="table-dark">
<tr>
    <th>User</th>
    <th>Ticket Number</th>
    <th>Action</th>
    <th>Date & Time</th>
</tr>
</thead>


</thead>

<tbody>

<?php

if(mysqli_num_rows($result) > 0)
{
    while($row = mysqli_fetch_assoc($result))
    {
?>

<tr>

<td><?php echo $row['FirstName']; ?></td>

<td>
<?php
echo ($row['TicketNumber'] != "") ? $row['TicketNumber'] : "-";
?>
</td>

<td>
<?php
echo ($row['Action'] != "") ? $row['Action'] : "-";
?>
</td>

<td><?php echo $row['CreatedDate']; ?></td>

</tr>
<?php
    }
}
else
{
?>

<tr>
    <td colspan="4" class="text-center text-danger">
        No activity found.
    </td>
</tr>

<?php
}
?>

</tbody>

</table>

</div>

</body>
</html>