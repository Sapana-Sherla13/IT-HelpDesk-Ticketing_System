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

// Search
$search = "";

if(isset($_GET['search']))
{
    $search = mysqli_real_escape_string($conn,$_GET['search']);
}

// Filters
$status = "";
$priority = "";
$category = "";
// Pagination

$limit = 10;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if($page < 1)
{
    $page = 1;
}

$start = ($page - 1) * $limit;
if(isset($_GET['status']))
{
    $status = mysqli_real_escape_string($conn,$_GET['status']);
}

if(isset($_GET['priority']))
{
    $priority = mysqli_real_escape_string($conn,$_GET['priority']);
}

if(isset($_GET['category']))
{
    $category = mysqli_real_escape_string($conn,$_GET['category']);
}


// Base Query

$query = "

SELECT

tickets.ID,
tickets.TicketNumber,
tickets.Title,
tickets.CreatedDate,
tickets.StatusId,
tickets.PriorityId,
tickets.CategoryId,

statuses.StatusName,

priorities.PriorityName,
categories.CategoryName,
CONCAT(users.FirstName,' ',users.LastName) AS CreatedByName

FROM tickets

LEFT JOIN statuses
ON tickets.StatusId=statuses.ID

LEFT JOIN priorities
ON tickets.PriorityId=priorities.ID
LEFT JOIN categories
ON tickets.CategoryId = categories.ID

LEFT JOIN users
ON tickets.CreatedBy = users.ID

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


// Count total records

$countQuery = $query;

$countResult = mysqli_query($conn,$countQuery);

$totalRows = mysqli_num_rows($countResult);

$totalPages = ceil($totalRows/$limit);


// Final Query

$query .= "

ORDER BY tickets.ID DESC

LIMIT $start,$limit

";
$result = mysqli_query($conn,$query);


// Category Dropdown

$categoryResult = mysqli_query($conn,"
SELECT *
FROM categories
WHERE IsActive=1
");
?>
<!DOCTYPE html>
<html>

<head>

<title>My Assigned Tickets</title>

<link rel="stylesheet" href="../assets/css/style.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>

<body>


<div class="container-fluid mt-4">

<div class="topbar">

<h2>My Tickets</h2>

<div class="profile">

<i class="fa-solid fa-circle-user fa-2x"></i>

<span><?php echo $_SESSION['name']; ?></span>

</div>

</div>

<div class="d-flex justify-content-between align-items-center mb-4">

    <h4 class="fw-bold text-primary mb-0">
    <i class="fa-solid fa-user-check"></i>
    My Assigned Tickets
    </h4>

    <span class="badge bg-primary fs-6 px-3 py-2">
        <?php echo $totalRows; ?> Ticket(s)
    </span>

</div>
<form method="GET" class="mb-4">
    <div class="d-flex justify-content-end mb-3">

    <a href="export_pdf.php?search=<?php echo $search; ?>&status=<?php echo $status; ?>&priority=<?php echo $priority; ?>&category=<?php echo $category; ?>"
       class="btn btn-danger me-2">

        <i class="fa-solid fa-file-pdf"></i>
        Export PDF

    </a>

    <a href="export_excel.php?search=<?php echo $search; ?>&status=<?php echo $status; ?>&priority=<?php echo $priority; ?>&category=<?php echo $category; ?>"
       class="btn btn-success">

        <i class="fa-solid fa-file-excel"></i>
        Export Excel

    </a>

</div>

<div class="row g-3">

    <!-- Search -->
    <div class="col-md-3">

        <input
        type="text"
        name="search"
        class="form-control"
        placeholder="Search Ticket"

        value="<?php echo $search; ?>">

    </div>


    <!-- Status -->

    <div class="col-md-2">

        <select
        name="status"
        class="form-select">

            <option value="">All Status</option>

            <option value="1" <?php if($status=="1") echo "selected"; ?>>
                Open
            </option>

            <option value="2" <?php if($status=="2") echo "selected"; ?>>
                Assigned
            </option>

            <option value="3" <?php if($status=="3") echo "selected"; ?>>
                In Progress
            </option>

            <option value="4" <?php if($status=="4") echo "selected"; ?>>
                Resolved
            </option>

            <option value="5" <?php if($status=="5") echo "selected"; ?>>
                Closed
            </option>

        </select>

    </div>


    <!-- Priority -->

    <div class="col-md-2">

        <select
        name="priority"
        class="form-select">

            <option value="">All Priority</option>

            <option value="1" <?php if($priority=="1") echo "selected"; ?>>
                Low
            </option>

            <option value="2" <?php if($priority=="2") echo "selected"; ?>>
                Medium
            </option>

            <option value="3" <?php if($priority=="3") echo "selected"; ?>>
                High
            </option>

            <option value="4" <?php if($priority=="4") echo "selected"; ?>>
                Critical
            </option>

        </select>

    </div>


    <!-- Category -->

    <div class="col-md-3">

        <select
        name="category"
        class="form-select">

            <option value="">All Categories</option>

            <?php
            while($cat=mysqli_fetch_assoc($categoryResult))
            {
            ?>

            <option
            value="<?php echo $cat['ID']; ?>"

            <?php
            if($category==$cat['ID'])
            {
                echo "selected";
            }
            ?>

            >

            <?php echo $cat['CategoryName']; ?>

            </option>

            <?php
            }
            ?>

        </select>

    </div>


    <!-- Buttons -->

    <div class="col-lg-2">

    <div class="d-grid gap-2">

        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-magnifying-glass"></i>
            Search
        </button>

        <a href="my_tickets.php" class="btn btn-secondary">
            <i class="fa-solid fa-rotate-left"></i>
            Reset
        </a>

    </div>

</div>

</div>

</form>
<div class="table-responsive">

<table class="table table-hover align-middle">

<thead class="table-primary">
<tr>

<th>Ticket No</th>

<th>Title</th>
<th>Created By</th>
<th>Category</th>
<th>Priority</th>

<th>Status</th>
<th>Date</th>
<th>Action</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($result) > 0)
{

while($row = mysqli_fetch_assoc($result))
{

$ticketId = $row['ID'];

// Check whether feedback is already submitted
$checkFeedback = "
SELECT ID
FROM ticketfeedback
WHERE TicketId='$ticketId'
AND UserId='$userId'
";

$feedbackResult = mysqli_query($conn,$checkFeedback);

?>

<tr>

<td><?php echo $row['TicketNumber']; ?></td>

<td><?php echo $row['Title']; ?></td>

<td>
    <span class="badge bg-info-subtle text-dark border">
        <?php echo $row['CreatedByName']; ?>
    </span>
</td>

<td><?php echo $row['CategoryName']; ?></td>
<td>

<?php

if($row['PriorityName']=="Low")
{
    echo '<span class="badge bg-success">Low</span>';
}
elseif($row['PriorityName']=="Medium")
{
    echo '<span class="badge bg-warning text-dark">Medium</span>';
}
elseif($row['PriorityName']=="High")
{
    echo '<span class="badge bg-danger">High</span>';
}
else
{
    echo '<span class="badge bg-dark">Critical</span>';
}

?>

</td>

<td>

<?php

if($row['StatusName']=="Open")
{
    echo '<span class="badge bg-primary">Open</span>';
}
elseif($row['StatusName']=="Assigned")
{
    echo '<span class="badge bg-info">Assigned</span>';
}
elseif($row['StatusName']=="In Progress")
{
    echo '<span class="badge bg-warning text-dark">In Progress</span>';
}
elseif($row['StatusName']=="Resolved")
{
    echo '<span class="badge bg-success">Resolved</span>';
}
else
{
    echo '<span class="badge bg-secondary">Closed</span>';
}

?>

</td>
<td>
    <?php echo date("d M Y", strtotime($row['CreatedDate'])); ?>
</td>
<td>

<a href="ticket_details.php?id=<?php echo $ticketId; ?>"
class="btn btn-info btn-sm">

<i class="fa-solid fa-eye"></i>

View

</a>

<?php

// Show Feedback button only for Closed tickets
if($row['StatusId'] == 5)
{

if(mysqli_num_rows($feedbackResult) == 0)
{

?>

<a href="feedback.php?id=<?php echo $ticketId; ?>"
class="btn btn-success btn-sm ms-1">

<i class="fa-solid fa-star"></i>

Feedback

</a>

<?php

}
else
{

?>

<span class="badge bg-success">

Feedback Submitted

</span>

<?php

}

}

?>

</td>

</tr>

<?php

}

}
else
{

?>

<tr>

<td colspan="5" class="text-center">

<div class="py-4">

<i class="fa-solid fa-ticket fa-3x text-secondary mb-3"></i>

<h5 class="fw-bold text-secondary">

No Tickets Found

</h5>

<p class="text-muted">

There are no tickets matching your search.

</p>

</div>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>
<nav class="mt-3">

<ul class="pagination justify-content-center">

<?php

for($i=1;$i<=$totalPages;$i++)
{

?>

<li class="page-item <?php echo ($page==$i)?'active':''; ?>">

<a
class="page-link"

href="?page=<?php echo $i; ?>

&search=<?php echo $search; ?>

&status=<?php echo $status; ?>

&priority=<?php echo $priority; ?>

&category=<?php echo $category; ?>">

<?php echo $i; ?>

</a>

</li>

<?php

}

?>

</ul>

</nav>
</div>

</div>

</div>

</body>

</html>