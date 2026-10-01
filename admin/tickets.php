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
tickets.ID,
tickets.TicketNumber,
tickets.Title,
tickets.CreatedDate,
tickets.AssignedTo,
statuses.StatusName,
priorities.PriorityName,
users.FirstName,
assignedUser.FirstName AS AssignedEmployee

FROM tickets

LEFT JOIN statuses
ON tickets.StatusId = statuses.ID

LEFT JOIN priorities
ON tickets.PriorityId = priorities.ID

LEFT JOIN users
ON tickets.CreatedBy = users.ID

LEFT JOIN users AS assignedUser
ON tickets.AssignedTo = assignedUser.ID
";

$where = array();

if(isset($_GET['search']) && $_GET['search'] != "")
{
    $search = mysqli_real_escape_string($conn,$_GET['search']);

    $where[] = "(tickets.TicketNumber LIKE '%$search%'
              OR tickets.Title LIKE '%$search%')";
}

if(isset($_GET['status']) && $_GET['status'] != "")
{
    $status = mysqli_real_escape_string($conn,$_GET['status']);

    $where[] = "tickets.StatusId='$status'";
}

if(isset($_GET['priority']) && $_GET['priority'] != "")
{
    $priority = mysqli_real_escape_string($conn,$_GET['priority']);

    $where[] = "tickets.PriorityId='$priority'";
}
$categoryResult = mysqli_query($conn,"SELECT * FROM categories WHERE IsActive=1");
if(isset($_GET['category']) && $_GET['category']!="")
{
    $category=mysqli_real_escape_string($conn,$_GET['category']);

    $where[]="tickets.CategoryId='$category'";
}
if(count($where)>0)
{
    $query .= " WHERE ".implode(" AND ",$where);
}

$query .= " ORDER BY tickets.ID DESC";


// Pagination

$limit = 10;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if($page < 1)
{
    $page = 1;
}

$start = ($page - 1) * $limit;



// Count total tickets

$countQuery = "

SELECT COUNT(*) AS total

FROM tickets

";


if(count($where)>0)
{
    $countQuery .= " WHERE ".implode(" AND ",$where);
}


$countResult = mysqli_query($conn,$countQuery);

$countRow = mysqli_fetch_assoc($countResult);

$totalTickets = $countRow['total'];

$totalPages = ceil($totalTickets / $limit);



// Add pagination limit

$query .= " LIMIT $start,$limit";


$result = mysqli_query($conn,$query);

?>

<!DOCTYPE html>
<html>

<head>
<link rel="stylesheet" href="../assets/css/style.css">
<title>All Tickets</title>
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">

<div class="container-fluid mt-4">


<div class="topbar">

    <h2>All Tickets</h2>

    <div class="profile">

        <i class="fa-solid fa-circle-user fa-2x"></i>

        <span>
            <?php echo $_SESSION['name']; ?>
        </span>

    </div>

</div>

<div class="card shadow-sm border-0 mb-4">

<div class="card-body">

<div class="d-flex justify-content-between align-items-center mb-3">

<h4 class="mb-0">
<i class="fa-solid fa-ticket"></i>
Ticket Management
</h4>

<a href="export_pdf.php" class="btn btn-danger">

<i class="fa-solid fa-file-pdf"></i>

Export PDF

</a>

</div>


<form method="GET">

<div class="row g-3 align-items-end">

    <!-- Search -->
    <div class="col-lg-3">
        <input
            type="text"
            name="search"
            class="form-control"
            placeholder="Search Ticket"
            value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
    </div>

    <!-- Status -->
    <div class="col-lg-2">
        <select name="status" class="form-control">
            <option value="">All Status</option>
            <option value="1" <?php if(isset($_GET['status']) && $_GET['status']=="1") echo "selected"; ?>>Open</option>
            <option value="2" <?php if(isset($_GET['status']) && $_GET['status']=="2") echo "selected"; ?>>Assigned</option>
            <option value="3" <?php if(isset($_GET['status']) && $_GET['status']=="3") echo "selected"; ?>>In Progress</option>
            <option value="4" <?php if(isset($_GET['status']) && $_GET['status']=="4") echo "selected"; ?>>Resolved</option>
            <option value="5" <?php if(isset($_GET['status']) && $_GET['status']=="5") echo "selected"; ?>>Closed</option>
        </select>
    </div>

    <!-- Priority -->
    <div class="col-lg-2">
        <select name="priority" class="form-control">
            <option value="">All Priority</option>
            <option value="1">Low</option>
            <option value="2">Medium</option>
            <option value="3">High</option>
            <option value="4">Critical</option>
        </select>
    </div>

    <!-- Category -->
<div class="col-lg-3">

    <select name="category" class="form-control">

        <option value="">All Categories</option>

        <?php while($cat = mysqli_fetch_assoc($categoryResult)){ ?>

            <option
            value="<?php echo $cat['ID']; ?>"
            <?php if(isset($_GET['category']) && $_GET['category']==$cat['ID']) echo "selected"; ?>>

                <?php echo $cat['CategoryName']; ?>

            </option>

        <?php } ?>

    </select>

</div>

<!-- Search Button -->
<div class="col-lg-2">

    <button type="submit" class="btn btn-primary w-100">

        <i class="fa-solid fa-magnifying-glass"></i>

        Search

    </button>

</div>

</div>

</form>

</div>

</div>
<div class="card shadow-sm border-0">

<div class="card-body">

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead class="table-dark">

<tr>

<th>Ticket No</th>
<th>Title</th>
<th>User</th>
<th>Priority</th>
<th>Status</th>
<th>Assigned To</th>
<th>Date</th>
<th>Action</th>

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

<td><?php echo $row['TicketNumber']; ?></td>

<td><?php echo $row['Title']; ?></td>

<td><?php echo $row['FirstName']; ?></td>

<td><?php echo $row['PriorityName']; ?></td>

<td><?php echo $row['StatusName']; ?></td>

<td>

<?php

if($row['AssignedEmployee']=="")
{
    echo "Not Assigned";
}
else
{
    echo $row['AssignedEmployee'];
}

?>

</td>

<td><?php echo $row['CreatedDate']; ?></td>

<td class="text-nowrap">

<a
href="ticket_details.php?id=<?php echo $row['ID']; ?>"
class="btn btn-info btn-sm me-1">
<i class="fa-solid fa-eye"></i>
</a>

<?php if($row['AssignedTo']==NULL){ ?>

<a
href="assign_ticket.php?id=<?php echo $row['ID']; ?>"
class="btn btn-primary btn-sm">
<i class="fa-solid fa-user-plus"></i>
</a>

<?php } else { ?>

<a
href="assign_ticket.php?id=<?php echo $row['ID']; ?>"
class="btn btn-warning btn-sm">
<i class="fa-solid fa-repeat"></i>
</a>

<?php } ?>

</td>

</tr>

<?php

}

}
else
{

?>

<tr>

<td colspan="8" class="text-center text-danger">

<strong>No tickets found.</strong>

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
<!-- Pagination -->

<nav class="mt-3">

<ul class="pagination justify-content-center">


<!-- Previous -->

<li class="page-item <?php if($page<=1){echo 'disabled';} ?>">

<a class="page-link"
href="?page=<?php echo $page-1; ?>&search=<?php echo $_GET['search'] ?? ''; ?>&status=<?php echo $_GET['status'] ?? ''; ?>&priority=<?php echo $_GET['priority'] ?? ''; ?>&category=<?php echo $_GET['category'] ?? ''; ?>">

Previous

</a>

</li>




<!-- Page Numbers -->

<?php

for($i=1;$i<=$totalPages;$i++)

{

?>

<li class="page-item <?php if($page==$i){echo 'active';} ?>">


<a class="page-link"

href="?page=<?php echo $i; ?>&search=<?php echo $_GET['search'] ?? ''; ?>&status=<?php echo $_GET['status'] ?? ''; ?>&priority=<?php echo $_GET['priority'] ?? ''; ?>&category=<?php echo $_GET['category'] ?? ''; ?>">


<?php echo $i; ?>


</a>


</li>


<?php

}

?>




<!-- Next -->


<li class="page-item <?php if($page >= $totalPages){echo 'disabled';} ?>">


<a class="page-link"

href="?page=<?php echo $page+1; ?>&search=<?php echo $_GET['search'] ?? ''; ?>&status=<?php echo $_GET['status'] ?? ''; ?>&priority=<?php echo $_GET['priority'] ?? ''; ?>&category=<?php echo $_GET['category'] ?? ''; ?>">


Next


</a>


</li>



</ul>

</nav>
</body>

</html>