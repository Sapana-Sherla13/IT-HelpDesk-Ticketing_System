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

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$query = "
SELECT
categories.CategoryName,
COUNT(tickets.ID) AS TotalTickets

FROM categories

LEFT JOIN tickets
ON categories.ID = tickets.CategoryId

GROUP BY categories.ID, categories.CategoryName

ORDER BY categories.CategoryName ASC
";

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>

<head>

    <title>Category Report</title>

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

            <h2>Category Report</h2>

            <div class="profile">

                <i class="fa-solid fa-circle-user fa-2x"></i>

                <span><?php echo $_SESSION['name']; ?></span>

            </div>

        </div>

        <div class="card shadow">

            <div class="card-body">

                <h4 class="mb-4">
                    <i class="fa-solid fa-layer-group"></i>
                    Ticket Category Report
                </h4>

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead class="table-dark">

                            <tr>

                                <th>Category</th>

                                <th>Total Tickets</th>

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
        <?php echo $row['CategoryName']; ?>
    </td>

    <td>
        <?php echo $row['TotalTickets']; ?>
    </td>

</tr>

<?php

    }

}
else
{

?>

<tr>

    <td colspan="2" class="text-center text-danger">
        No Categories Found
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