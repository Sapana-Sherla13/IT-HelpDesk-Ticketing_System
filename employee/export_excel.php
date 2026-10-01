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

$userId = $_SESSION['user_id'];

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn,$_GET['search']) : "";
$status = isset($_GET['status']) ? mysqli_real_escape_string($conn,$_GET['status']) : "";
$priority = isset($_GET['priority']) ? mysqli_real_escape_string($conn,$_GET['priority']) : "";
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn,$_GET['category']) : "";

$query = "

SELECT

tickets.TicketNumber,
tickets.Title,
categories.CategoryName,
priorities.PriorityName,
statuses.StatusName,
tickets.CreatedDate

FROM tickets

LEFT JOIN categories
ON tickets.CategoryId=categories.ID

LEFT JOIN priorities
ON tickets.PriorityId=priorities.ID

LEFT JOIN statuses
ON tickets.StatusId=statuses.ID

WHERE tickets.AssignedTo='$userId'

";

if($search!="")
{
    $query .= "
    AND
    (
        tickets.TicketNumber LIKE '%$search%'
        OR
        tickets.Title LIKE '%$search%'
    )
    ";
}

if($status!="")
{
    $query .= " AND tickets.StatusId='$status' ";
}

if($priority!="")
{
    $query .= " AND tickets.PriorityId='$priority' ";
}

if($category!="")
{
    $query .= " AND tickets.CategoryId='$category' ";
}

$query .= " ORDER BY tickets.ID DESC ";

$result = mysqli_query($conn,$query);

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=MyTickets.xls");

echo "<table border='1'>";

echo "
<tr>
<th>Ticket No</th>
<th>Title</th>
<th>Category</th>
<th>Priority</th>
<th>Status</th>
<th>Created Date</th>
</tr>
";

while($row=mysqli_fetch_assoc($result))
{
    echo "<tr>";

    echo "<td>".$row['TicketNumber']."</td>";
    echo "<td>".$row['Title']."</td>";
    echo "<td>".$row['CategoryName']."</td>";
    echo "<td>".$row['PriorityName']."</td>";
    echo "<td>".$row['StatusName']."</td>";
    echo "<td>".$row['CreatedDate']."</td>";

    echo "</tr>";
}

echo "</table>";
?>