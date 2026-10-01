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


// Search
$search = "";

if(isset($_GET['search']))
{
    $search = mysqli_real_escape_string($conn,$_GET['search']);
}


// Pagination
$limit = 10;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if($page < 1)
{
    $page = 1;
}

$start = ($page - 1) * $limit;



// Count Users

$countQuery = "

SELECT COUNT(*) AS total

FROM users

WHERE

FirstName LIKE '%$search%'

OR

LastName LIKE '%$search%'

OR

Email LIKE '%$search%'

";


$countResult = mysqli_query($conn,$countQuery);

$countRow = mysqli_fetch_assoc($countResult);

$totalUsers = $countRow['total'];

$totalPages = ceil($totalUsers / $limit);



// Fetch Users

$query = "

SELECT

users.ID,

users.FirstName,

users.LastName,

users.Email,

roles.RoleName,

users.IsActive


FROM users


LEFT JOIN roles

ON users.RoleId = roles.ID


WHERE

users.FirstName LIKE '%$search%'

OR

users.LastName LIKE '%$search%'

OR

users.Email LIKE '%$search%'


ORDER BY users.ID DESC


LIMIT $start,$limit

";


$result = mysqli_query($conn,$query);


?>


<!DOCTYPE html>
<html>

<head>

<link rel="stylesheet" href="../assets/css/style.css">

<title>Manage Users</title>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


</head>



<body>


<?php include 'includes/sidebar.php'; ?>


<div class="main-content">


<div class="container-fluid mt-4">



<h2>Manage Users</h2>



<a href="add_user.php" class="btn btn-primary mb-3">

<i class="fa fa-user-plus"></i> Add User

</a>





<form method="GET" class="row mb-3">


<div class="col-md-4">


<input 

type="text"

name="search"

class="form-control"

placeholder="Search name or email"

value="<?php echo $search; ?>">



</div>



<div class="col-md-2">


<button class="btn btn-primary">

<i class="fa fa-search"></i>

Search

</button>


</div>



</form>






<table class="table table-bordered table-striped">


<tr>

<th>ID</th>

<th>Name</th>

<th>Email</th>

<th>Role</th>

<th>Status</th>

<th>Action</th>


</tr>





<?php


if(mysqli_num_rows($result)>0)

{


while($row=mysqli_fetch_assoc($result))

{


?>


<tr>


<td>

<?php echo $row['ID']; ?>

</td>



<td>

<?php echo $row['FirstName']." ".$row['LastName']; ?>

</td>



<td>

<?php echo $row['Email']; ?>

</td>



<td>

<?php echo $row['RoleName']; ?>

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



<a href="edit_user.php?id=<?php echo $row['ID']; ?>"

class="btn btn-primary btn-sm">

<i class="fa fa-edit"></i>

Edit

</a>





<?php if($row['RoleName'] != 'Admin'){ ?>



<?php if($row['IsActive']==1){ ?>


<a href="toggle_user.php?id=<?php echo $row['ID']; ?>&status=0"

class="btn btn-warning btn-sm"

onclick="return confirm('Deactivate this user?')">


<i class="fa fa-user-slash"></i>

Deactivate


</a>



<?php } else { ?>



<a href="toggle_user.php?id=<?php echo $row['ID']; ?>&status=1"

class="btn btn-success btn-sm"

onclick="return confirm('Activate this user?')">


<i class="fa fa-user-check"></i>

Activate


</a>



<?php } ?>



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

<td colspan="6" class="text-center">

No Users Found

</td>

</tr>


<?php

}


?>



</table>





<!-- Pagination -->


<nav>


<ul class="pagination justify-content-center">





<!-- Previous -->


<li class="page-item <?php if($page<=1){echo 'disabled';} ?>">


<a class="page-link"

href="?page=<?php echo $page-1; ?>&search=<?php echo $search; ?>">


Previous


</a>


</li>






<!-- Pages -->


<?php


for($i=1;$i<=$totalPages;$i++)

{


?>


<li class="page-item <?php if($page==$i){echo 'active';} ?>">



<a class="page-link"

href="?page=<?php echo $i; ?>&search=<?php echo $search; ?>">



<?php echo $i; ?>



</a>


</li>



<?php


}


?>






<!-- Next -->


<li class="page-item <?php if($page >= $totalPages){echo 'disabled';} ?>">



<a class="page-link"

href="?page=<?php echo $page+1; ?>&search=<?php echo $search; ?>">



Next


</a>



</li>





</ul>


</nav>




</div>


</div>



</body>

</html>