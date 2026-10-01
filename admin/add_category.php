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

if(isset($_POST['add_category']))
{
    $category = trim($_POST['category']);

    // Check if category already exists
    $check = "SELECT * FROM categories WHERE CategoryName='$category'";
    $result = mysqli_query($conn, $check);

    if(mysqli_num_rows($result) > 0)
    {
        echo "<script>alert('Category already exists.');</script>";
    }
    else
    {
        $query = "INSERT INTO categories (CategoryName, IsActive)
                  VALUES ('$category', 1)";

        if(mysqli_query($conn, $query))
        {
            echo "<script>
            alert('Category Added Successfully');
            window.location='categories.php';
            </script>";
            exit();
        }
        else
        {
            echo "Error: ".mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Add Category</title>

<link rel="stylesheet" href="../assets/css/style.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">

<div class="container-fluid mt-4">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h3>Add Category</h3>

</div>

<div class="card-body">

<form method="POST">

<div class="mb-3">

<label>Category Name</label>

<input
type="text"
name="category"
class="form-control"
placeholder="Enter Category Name"
required>

</div>

<button
type="submit"
name="add_category"
class="btn btn-success">

Add Category

</button>

<a href="categories.php"
class="btn btn-secondary">

Cancel

</a>

</form>

</div>

</div>

</div>

</div>

</body>
</html>