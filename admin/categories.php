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

$query = "SELECT * FROM categories ORDER BY ID ASC";
$result = mysqli_query($conn,$query);
?>

<!DOCTYPE html>
<html>
<head>

<title>Manage Categories</title>

<link rel="stylesheet" href="../assets/css/style.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">

<div class="container-fluid mt-4">

<h2>Manage Categories</h2>

<a href="add_category.php" class="btn btn-primary mb-3">

<i class="fa fa-plus"></i> Add Category

</a>

<table class="table table-bordered table-hover shadow">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Category Name</th>
<th>Status</th>
<th>Action</th>

</tr>

</thead>

<tbody>

<?php

while($row=mysqli_fetch_assoc($result))
{

?>

<tr>

<td>

<?php echo $row['ID']; ?>

</td>

<td>

<?php echo $row['CategoryName']; ?>

</td>

<td>

<?php

if($row['IsActive']==1)
{
    echo '<span class="badge bg-success">Active</span>';
}
else
{
    echo '<span class="badge bg-danger">Inactive</span>';
}

?>

</td>

<td>

<a href="edit_category.php?id=<?php echo $row['ID']; ?>"
class="btn btn-primary btn-sm">

<i class="fa fa-edit"></i>

Edit

</a>

<?php

if($row['IsActive']==1)
{

?>

<a
href="toggle_category.php?id=<?php echo $row['ID']; ?>&status=0"
class="btn btn-warning btn-sm"
onclick="return confirm('Deactivate this category?')">

<i class="fa fa-ban"></i>

Deactivate

</a>

<?php

}
else
{

?>

<a
href="toggle_category.php?id=<?php echo $row['ID']; ?>&status=1"
class="btn btn-success btn-sm"
onclick="return confirm('Activate this category?')">

<i class="fa fa-check"></i>

Activate

</a>

<?php

}

?>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

</div>

</body>

</html>