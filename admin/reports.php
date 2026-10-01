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
users.ID,
users.FirstName,
users.LastName,

COUNT(tickets.ID) AS TotalTickets,

SUM(CASE WHEN tickets.StatusId=1 THEN 1 ELSE 0 END) AS OpenTickets,

SUM(CASE WHEN tickets.StatusId=2 THEN 1 ELSE 0 END) AS AssignedTickets,

SUM(CASE WHEN tickets.StatusId=3 THEN 1 ELSE 0 END) AS ProgressTickets,

SUM(CASE WHEN tickets.StatusId=4 THEN 1 ELSE 0 END) AS ResolvedTickets,

SUM(CASE WHEN tickets.StatusId=5 THEN 1 ELSE 0 END) AS ClosedTickets

FROM users

LEFT JOIN tickets
ON users.ID = tickets.AssignedTo

WHERE users.RoleId = 2

GROUP BY users.ID

ORDER BY users.FirstName ASC
";

$result = mysqli_query($conn,$query);
?>
<!DOCTYPE html>
<html>

<head>

<title>Employee Report</title>

<link rel="stylesheet" href="../assets/css/style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">

<div class="container-fluid mt-4">

<div class="topbar">

<h2>Employee Report</h2>

<div class="profile">

<i class="fa-solid fa-circle-user fa-2x"></i>

<span><?php echo $_SESSION['name']; ?></span>

</div>

</div>

<div class="card shadow">

<div class="card-body">

<h4 class="mb-4">

<i class="fa-solid fa-chart-column"></i>

Employee Performance Report

</h4>

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Employee</th>

<th>Total</th>

<th>Open</th>

<th>Assigned</th>

<th>In Progress</th>

<th>Resolved</th>

<th>Closed</th>

</tr>

</thead>

<tbody>
    <?php

if(mysqli_num_rows($result)>0)
{

while($row=mysqli_fetch_assoc($result))
{

?>

<tr>

<td>

<?php echo $row['FirstName']." ".$row['LastName']; ?>

</td>

<td>

<?php echo $row['TotalTickets']; ?>

</td>

<td>

<?php echo $row['OpenTickets']; ?>

</td>

<td>

<?php echo $row['AssignedTickets']; ?>

</td>

<td>

<?php echo $row['ProgressTickets']; ?>

</td>

<td>

<?php echo $row['ResolvedTickets']; ?>

</td>

<td>

<?php echo $row['ClosedTickets']; ?>

</td>

</tr>

<?php

}

}
else
{

?>

<tr>

<td colspan="7" class="text-center text-danger">

No Employees Found

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

</div>

</div>

</div>

</body>

</html>