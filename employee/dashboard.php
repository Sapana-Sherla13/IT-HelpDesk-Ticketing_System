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

$employeeId = $_SESSION['user_id'];

/* Greeting */

$hour = date("H");

if($hour < 12)
{
    $greeting = "Good Morning";
}
elseif($hour < 17)
{
    $greeting = "Good Afternoon";
}
else
{
    $greeting = "Good Evening";
}


/* Dashboard Counts */

$totalQuery = "
SELECT COUNT(*) total
FROM tickets
WHERE
(
    CreatedBy='$employeeId'
    OR
    AssignedTo='$employeeId'
)
";

$totalTickets = mysqli_fetch_assoc(mysqli_query($conn,$totalQuery))['total'];


$openQuery = "
SELECT COUNT(*) total
FROM tickets
WHERE
(
    CreatedBy='$employeeId'
    OR
    AssignedTo='$employeeId'
)
AND StatusId=1
";

$openTickets = mysqli_fetch_assoc(mysqli_query($conn,$openQuery))['total'];
/* Assigned Tickets */

$assignedQuery = "
SELECT COUNT(*) total
FROM tickets
WHERE
(
    CreatedBy='$employeeId'
    OR
    AssignedTo='$employeeId'
)
AND StatusId=2
";

$assignedTickets = mysqli_fetch_assoc(mysqli_query($conn,$assignedQuery))['total'];
$progressQuery = "
SELECT COUNT(*) total
FROM tickets
WHERE
(
    CreatedBy='$employeeId'
    OR
    AssignedTo='$employeeId'
)
AND StatusId=3
";

$progressTickets = mysqli_fetch_assoc(mysqli_query($conn,$progressQuery))['total'];
/* Resolved Tickets */

$resolvedQuery = "
SELECT COUNT(*) total
FROM tickets
WHERE
(
    CreatedBy='$employeeId'
    OR
    AssignedTo='$employeeId'
)
AND StatusId=4
";

$resolvedTickets = mysqli_fetch_assoc(mysqli_query($conn,$resolvedQuery))['total'];

$closedQuery = "
SELECT COUNT(*) total
FROM tickets
WHERE
(
    CreatedBy='$employeeId'
    OR
    AssignedTo='$employeeId'
)
AND StatusId=5
";

$closedTickets = mysqli_fetch_assoc(mysqli_query($conn,$closedQuery))['total'];

/* Latest 5 Tickets */

$query = "

SELECT

tickets.ID,
tickets.TicketNumber,
tickets.Title,
tickets.CreatedDate,

categories.CategoryName,

statuses.StatusName,

priorities.PriorityName

FROM tickets

LEFT JOIN statuses
ON tickets.StatusId=statuses.ID

LEFT JOIN priorities
ON tickets.PriorityId=priorities.ID
LEFT JOIN categories
ON tickets.CategoryId = categories.ID

WHERE
(
    tickets.CreatedBy='$employeeId'
    OR
    tickets.AssignedTo='$employeeId'
)

ORDER BY tickets.ID DESC

LIMIT 5

";

$result = mysqli_query($conn,$query);

?>


<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>Employee Dashboard</title>

<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/dashboard.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>

<body>
<?php include '../includes/sidebar.php'; ?>

<div class="main-content">
<div class="container-fluid dashboard-container">
<?php
if(isset($_SESSION['success_message']))
{
?>

<div id="successAlert" class="alert alert-success">

    <?php
    echo $_SESSION['success_message'];
    unset($_SESSION['success_message']);
    ?>

</div>

<?php
}
?>
<!-- Welcome Banner -->

<div class="welcome-banner mb-4">

    <div class="row align-items-center">

        <!-- Left Side -->

        <div class="col-lg-8">

            <h1 class="dashboard-title">
                Employee Dashboard
            </h1>

            <p class="dashboard-subtitle">

                Welcome back,

                <strong><?php echo $_SESSION['name']; ?></strong>!

                Here's what's happening with your tickets.

            </p>

        </div>

        <!-- Right Side -->

        <div class="col-lg-4 text-end">

            <!-- Profile -->

            <div class="profile-dropdown dropdown mb-3">

                <button
                    class="btn btn-light dropdown-toggle shadow-sm"
                    type="button"
                    data-bs-toggle="dropdown">

                    <i class="fa-solid fa-circle-user me-2"></i>

                    <?php echo $_SESSION['name']; ?>

                </button>

                <ul class="dropdown-menu dropdown-menu-end">

                    <li>
                        <a class="dropdown-item" href="profile.php">
                            <i class="fa-solid fa-user me-2"></i>
                            My Profile
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="change_password.php">
                            <i class="fa-solid fa-key me-2"></i>
                            Change Password
                        </a>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <a class="dropdown-item text-danger" href="../logout.php">
                            <i class="fa-solid fa-right-from-bracket me-2"></i>
                            Logout
                        </a>
                    </li>

                </ul>

            </div>


        </div>

    </div>

</div>

</div>
<div class="cards-section">

<div class="row g-3">

    <!-- Total -->
    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 mb-4">
        <div class="stats-card purple">

            <div class="stats-top">

                <div class="stats-icon bg-purple">
                    <i class="fa-solid fa-ticket"></i>
                </div>

                <div class="stats-number">
                    <?php echo $totalTickets; ?>
                </div>

            </div>

            <h5>Total Tickets</h5>

            <p>All tickets created and assigned.</p>

        </div>
    </div>

    <!-- Open -->

    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 mb-4">
        <div class="stats-card blue">

            <div class="stats-top">

                <div class="stats-icon bg-blue">
                    <i class="fa-solid fa-folder-open"></i>
                </div>

                <div class="stats-number">
                    <?php echo $openTickets; ?>
                </div>

            </div>

            <h5>Open Tickets</h5>

            <p>Waiting for assignment.</p>

        </div>
    </div>

    <!-- Assigned -->
<div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 mb-4">
    
        <div class="stats-card cyan">

            <div class="stats-top">

                <div class="stats-icon bg-cyan">
                    <i class="fa-solid fa-user-check"></i>
                </div>

                <div class="stats-number">
                    <?php echo $assignedTickets; ?>
                </div>

            </div>

            <h5>Assigned</h5>

            <p>Assigned to employees.</p>

        </div>
    </div>

    <!-- Progress -->
<div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 mb-4">
    

        <div class="stats-card orange">

            <div class="stats-top">

                <div class="stats-icon bg-orange">
                    <i class="fa-solid fa-spinner"></i>
                </div>

                <div class="stats-number">
                    <?php echo $progressTickets; ?>
                </div>

            </div>

            <h5>In Progress</h5>

            <p>Currently being worked on.</p>

        </div>

    </div>

    <!-- Resolved -->

    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 mb-4">

        <div class="stats-card green">

            <div class="stats-top">

                <div class="stats-icon bg-green">
                    <i class="fa-solid fa-circle-check"></i>
                </div>

                <div class="stats-number">
                    <?php echo $resolvedTickets; ?>
                </div>

            </div>

            <h5>Resolved</h5>

            <p>Successfully resolved tickets.</p>

        </div>

    </div>

    <!-- Closed -->
<div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 mb-4">
    

        <div class="stats-card red">

            <div class="stats-top">

                <div class="stats-icon bg-red">
                    <i class="fa-solid fa-lock"></i>
                </div>

                <div class="stats-number">
                    <?php echo $closedTickets; ?>
                </div>

            </div>

            <h5>Closed</h5>

            <p>Completed and closed tickets.</p>

        </div>

    </div>

</div>
</div>




<!-- Recent Tickets -->

<div class="dashboard-table">

<div class="table-header">

    <div>

        <i class="fa-solid fa-clock-rotate-left"></i>

        Recent Assigned Tickets

    </div>

    <a href="my_tickets.php" class="view-all">

        View All

        <i class="fa-solid fa-arrow-right"></i>

    </a>

</div>

<div class="table-body">

<div class="table-responsive">

<table class="table custom-table align-middle">

<thead>

<tr>

<th>Ticket No</th>
<th>Title</th>
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

?>

<tr>

    <td>

<a href="ticket_details.php?id=<?php echo $row['ID']; ?>" class="ticket-link">

    <?php echo $row['TicketNumber']; ?>

</a>

</td>

    <td>
        <?php echo $row['Title']; ?>
    </td>
    <td>

<?php

if($row['CategoryName']=="Hardware")
{
    echo '<span class="badge bg-primary-subtle text-primary border border-primary">Hardware</span>';
}
elseif($row['CategoryName']=="Software")
{
    echo '<span class="badge bg-secondary-subtle text-secondary border border-secondary">Software</span>';
}
elseif($row['CategoryName']=="Network")
{
    echo '<span class="badge bg-success-subtle text-success border border-success">Network</span>';
}
elseif($row['CategoryName']=="Email")
{
    echo '<span class="badge bg-warning-subtle text-warning border border-warning">Email</span>';
}
else
{
    echo '<span class="badge bg-light text-dark border">'.$row['CategoryName'].'</span>';
}

?>

</td>

    <td>

        <?php

        if($row['PriorityName']=="Low")
        {
            echo '<span class="badge-resolved">Low</span>';
        }
        elseif($row['PriorityName']=="Medium")
        {
            echo '<span class="badge-open">Medium</span>';
        }
        elseif($row['PriorityName']=="High")
        {
            echo '<span class="badge-closed">High</span>';
        }
        else
        {
            echo '<span class="badge-assigned">Critical</span>';
        }

        ?>

    </td>

    <td>

        <?php

        if($row['StatusName']=="Open")
{
    echo '<span class="badge-open">Open</span>';
}
elseif($row['StatusName']=="Assigned")
{
    echo '<span class="badge-assigned">Assigned</span>';
}
elseif($row['StatusName']=="In Progress")
{
    echo '<span class="badge-progress">In Progress</span>';
}
elseif($row['StatusName']=="Resolved")
{
    echo '<span class="badge-resolved">Resolved</span>';
}
else
{
    echo '<span class="badge-closed">Closed</span>';
}

        ?>

    </td>

    <td>
        <?php echo date("d M Y", strtotime($row['CreatedDate'])); ?>
    </td>

    <td>

        <a href="ticket_details.php?id=<?php echo $row['ID']; ?>"
           class="btn btn-info btn-sm">
            <i class="fa-solid fa-eye"></i> View
        </a>

    </td>

</tr>

<?php

    }

}
else
{

?>

<tr>

<td colspan="7" 
class="text-center text-danger">

No Assigned Tickets Found

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
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>

setTimeout(function(){

    var alert = document.getElementById("successAlert");

    if(alert)
    {
        alert.style.transition = "opacity 0.5s";
        alert.style.opacity = "0";

        setTimeout(function(){
            alert.remove();
        },500);
    }

},5000);

</script>
<script>

function updateTime(){

const now=new Date();

document.getElementById("liveTime").innerHTML=

now.toLocaleTimeString("en-IN",{

hour:'2-digit',

minute:'2-digit',

second:'2-digit',

hour12:true

});

}

updateTime();

setInterval(updateTime,1000);

</script>
</body>

</html>