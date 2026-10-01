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

users.FirstName,
users.LastName,

SUM(CASE WHEN tickets.StatusId = 2 THEN 1 ELSE 0 END) AS Assigned,

SUM(CASE WHEN tickets.StatusId = 3 THEN 1 ELSE 0 END) AS InProgress,

SUM(CASE WHEN tickets.StatusId = 4 THEN 1 ELSE 0 END) AS Resolved,

SUM(CASE WHEN tickets.StatusId = 5 THEN 1 ELSE 0 END) AS Closed

FROM users

LEFT JOIN tickets
ON users.ID = tickets.AssignedTo

WHERE users.RoleId = 2

GROUP BY users.ID

ORDER BY users.FirstName
";

$result = mysqli_query($conn,$query);

?>

<!DOCTYPE html>
<html>

<head>
<link rel="stylesheet" href="../assets/css/style.css">
<title>Employee Report</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid mt-4">

<h2>Employee Performance Report</h2>

<hr>

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Employee</th>
<th>Assigned</th>
<th>In Progress</th>
<th>Resolved</th>
<th>Closed</th>

</tr>

</thead>

<tbody>

<?php

while($row = mysqli_fetch_assoc($result))
{

?>

<tr>

<td>
<?php echo $row['FirstName']." ".$row['LastName']; ?>
</td>

<td>
<?php echo $row['Assigned']; ?>
</td>

<td>
<?php echo $row['InProgress']; ?>
</td>

<td>
<?php echo $row['Resolved']; ?>
</td>

<td>
<?php echo $row['Closed']; ?>
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