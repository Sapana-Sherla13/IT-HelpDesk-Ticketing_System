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

/* ===========================
   Ticket Status Chart Data
=========================== */

$statusQuery = "
SELECT
    statuses.StatusName,
    COUNT(tickets.ID) AS Total
FROM statuses
LEFT JOIN tickets
ON statuses.ID = tickets.StatusId
GROUP BY statuses.ID
";

$statusResult = mysqli_query($conn, $statusQuery);

$labels = [];
$data = [];

while ($row = mysqli_fetch_assoc($statusResult))
{
    $labels[] = $row['StatusName'];
    $data[] = $row['Total'];
}


/* ===========================
   Dashboard Cards
=========================== */

// Total Tickets
$totalQuery = "SELECT COUNT(*) AS total FROM tickets";
$totalResult = mysqli_query($conn, $totalQuery);
$totalTickets = mysqli_fetch_assoc($totalResult)['total'];


// Open Tickets
$openQuery = "SELECT COUNT(*) AS open FROM tickets WHERE StatusId=1";
$openResult = mysqli_query($conn, $openQuery);
$openTickets = mysqli_fetch_assoc($openResult)['open'];


// Assigned Tickets
$assignedQuery = "SELECT COUNT(*) AS assigned FROM tickets WHERE StatusId=2";
$assignedResult = mysqli_query($conn, $assignedQuery);
$assignedTickets = mysqli_fetch_assoc($assignedResult)['assigned'];


// In Progress
$progressQuery = "SELECT COUNT(*) AS progress FROM tickets WHERE StatusId=3";
$progressResult = mysqli_query($conn, $progressQuery);
$progressTickets = mysqli_fetch_assoc($progressResult)['progress'];


// Resolved
$resolvedQuery = "SELECT COUNT(*) AS resolved FROM tickets WHERE StatusId=4";
$resolvedResult = mysqli_query($conn, $resolvedQuery);
$resolvedTickets = mysqli_fetch_assoc($resolvedResult)['resolved'];


// Closed
$closedQuery = "SELECT COUNT(*) AS closed FROM tickets WHERE StatusId=5";
$closedResult = mysqli_query($conn, $closedQuery);
$closedTickets = mysqli_fetch_assoc($closedResult)['closed'];


/* ===========================
   Recent Tickets
=========================== */

$recentQuery = "
SELECT
    tickets.ID,
    tickets.TicketNumber,
    tickets.Title,
    statuses.StatusName,
    priorities.PriorityName,
    tickets.CreatedDate

FROM tickets

LEFT JOIN statuses
ON tickets.StatusId = statuses.ID

LEFT JOIN priorities
ON tickets.PriorityId = priorities.ID

ORDER BY tickets.ID DESC

LIMIT 5
";

$recentResult = mysqli_query($conn, $recentQuery);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Admin Dashboard</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<!-- Main CSS -->
<link rel="stylesheet" href="../assets/css/style.css">

<!-- Dashboard CSS -->
<link rel="stylesheet" href="../assets/css/dashboard.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>


<body>

<?php include 'includes/sidebar.php'; ?>


<div class="main-content">

<div class="container-fluid dashboard-container">


<!-- =========================
     WELCOME HEADER
========================= -->

<div class="welcome-banner mb-4">

    <div class="row align-items-center">

        <div class="col-lg-8">

            <h1 class="dashboard-title">
                Admin Dashboard
            </h1>

            <p class="dashboard-subtitle">

                Welcome back,
                <strong><?php echo $_SESSION['name']; ?></strong>!

                Here's what's happening with your tickets.

            </p>

        </div>


        <div class="col-lg-4 text-end">

            <div class="profile-dropdown dropdown">

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

                        <a class="dropdown-item" href="../logout.php">

                            <i class="fa-solid fa-right-from-bracket me-2"></i>

                            Logout

                        </a>

                    </li>

                </ul>

            </div>

        </div>

    </div>

</div>



<!-- =========================
     STATISTICS CARDS
========================= -->

<div class="cards-section">

<div class="row g-3">


<!-- Total Tickets -->

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

    <p>All tickets in the system.</p>

</div>

</div>



<!-- Open Tickets -->

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



<!-- Assigned Tickets -->

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



<!-- In Progress -->

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

    <p>Successfully resolved.</p>

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

    <p>Completed tickets.</p>

</div>

</div>


</div>

</div>



<!-- =========================
     CHARTS
========================= -->

<div class="row g-4 mt-2">


<!-- Ticket Statistics -->

<div class="col-lg-6">

<div class="card shadow-sm border-0">

<div class="card-body">

<h4 class="mb-3">

<i class="fa-solid fa-chart-column"></i>

Ticket Statistics

</h4>

<div style="height:320px;">

<canvas id="ticketChart"></canvas>

</div>

</div>

</div>

</div>



<!-- Ticket Status -->

<div class="col-lg-6">

<div class="card shadow-sm border-0">

<div class="card-body">

<h4 class="mb-3">

<i class="fa-solid fa-chart-pie"></i>

Ticket Status

</h4>

<div style="height:320px;">

<canvas id="statusChart"></canvas>

</div>

</div>

</div>

</div>


</div>



<!-- =========================
     RECENT TICKETS
========================= -->

<div class="dashboard-table mt-4">


<div class="table-header">

<div>

<i class="fa-solid fa-clock-rotate-left"></i>

Recent Tickets

</div>


<a href="tickets.php" class="view-all">

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

<th>Priority</th>

<th>Status</th>

<th>Date</th>

<th>Action</th>

</tr>

</thead>


<tbody>


<?php

if(mysqli_num_rows($recentResult) > 0)
{

while($row = mysqli_fetch_assoc($recentResult))
{

?>


<tr>


<td>

<a
href="ticket_details.php?id=<?php echo $row['ID']; ?>"
class="ticket-link">

<?php echo $row['TicketNumber']; ?>

</a>

</td>


<td>

<?php echo $row['Title']; ?>

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

<?php

echo date(
    "d M Y",
    strtotime($row['CreatedDate'])
);

?>

</td>


<td>

<a
href="ticket_details.php?id=<?php echo $row['ID']; ?>"
class="btn btn-info btn-sm">

<i class="fa-solid fa-eye"></i>

View

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

<td colspan="6"
class="text-center text-danger">

No Tickets Found

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



<!-- Chart.js -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>

/* ===========================
   Ticket Statistics
=========================== */

const ticketCtx =
document.getElementById('ticketChart');


new Chart(ticketCtx, {

    type: 'bar',

    data: {

        labels: [
            'Open',
            'Assigned',
            'In Progress',
            'Resolved',
            'Closed'
        ],

        datasets: [{

            label: 'Tickets',

            data: [

                <?php echo $openTickets; ?>,

                <?php echo $assignedTickets; ?>,

                <?php echo $progressTickets; ?>,

                <?php echo $resolvedTickets; ?>,

                <?php echo $closedTickets; ?>

            ],

            backgroundColor: [

                '#2563EB',

                '#F59E0B',

                '#F97316',

                '#22C55E',

                '#64748B'

            ],

            borderWidth: 0,

            borderRadius: 6

        }]

    },

    options: {

        responsive: true,

        maintainAspectRatio: false,

        plugins: {

            legend: {

                display: false

            }

        },

        scales: {

            y: {

                beginAtZero: true,

                ticks: {

                    precision: 0

                }

            }

        }

    }

});



/* ===========================
   Ticket Status
=========================== */

const statusCtx =
document.getElementById('statusChart');


new Chart(statusCtx, {

    type: 'pie',

    data: {

        labels:
        <?php echo json_encode($labels); ?>,

        datasets: [{

            data:
            <?php echo json_encode($data); ?>,

            backgroundColor: [

                '#2563EB',

                '#F59E0B',

                '#F97316',

                '#22C55E',

                '#64748B'

            ],

            borderWidth: 2,

            borderColor: '#ffffff'

        }]

    },

    options: {

        responsive: true,

        maintainAspectRatio: false,

        plugins: {

            legend: {

                position: 'bottom'

            }

        }

    }

});

</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>