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

if(!isset($_GET['id']))
{
    header("Location: categories.php");
    exit();
}

$id = $_GET['id'];

// Fetch Category
$query = "SELECT * FROM categories WHERE ID='$id'";
$result = mysqli_query($conn,$query);
$category = mysqli_fetch_assoc($result);

if(!$category)
{
    die("Category not found.");
}

// Update Category
if(isset($_POST['update_category']))
{
    $categoryName = trim($_POST['category']);

    // Check duplicate category
    $check = "SELECT * FROM categories
              WHERE CategoryName='$categoryName'
              AND ID != '$id'";

    $checkResult = mysqli_query($conn,$check);

    if(mysqli_num_rows($checkResult) > 0)
    {
        echo "<script>alert('Category already exists.');</script>";
    }
    else
    {
        $update = "UPDATE categories
                   SET CategoryName='$categoryName'
                   WHERE ID='$id'";

        if(mysqli_query($conn,$update))
        {
            echo "<script>
            alert('Category Updated Successfully');
            window.location='categories.php';
            </script>";
            exit();
        }
        else
        {
            echo mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Edit Category</title>

<link rel="stylesheet" href="../assets/css/style.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">

<div class="container-fluid mt-4">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h3>Edit Category</h3>

</div>

<div class="card-body">

<form method="POST">

<div class="mb-3">

<label>Category Name</label>

<input
type="text"
name="category"
class="form-control"
value="<?php echo $category['CategoryName']; ?>"
required>

</div>

<button
type="submit"
name="update_category"
class="btn btn-success">

<i class="fa fa-save"></i> Update

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