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

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$ticketId = $_GET['id'];
$userId = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html>

<head>

    <title>Ticket Feedback</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h3>
<i class="fa-solid fa-star"></i>
Ticket Feedback
</h3>

</div>

<div class="card-body">

<form method="POST">

<div class="mb-3">

<label class="form-label">

Rating

</label>

<select name="rating" class="form-control" required>

<option value="">Select Rating</option>

<option value="5">⭐⭐⭐⭐⭐ Excellent</option>

<option value="4">⭐⭐⭐⭐ Very Good</option>

<option value="3">⭐⭐⭐ Good</option>

<option value="2">⭐⭐ Fair</option>

<option value="1">⭐ Poor</option>

</select>

</div>

<div class="mb-3">

<label class="form-label">

Feedback

</label>

<textarea
name="feedback"
class="form-control"
rows="5"
placeholder="Write your feedback..."
required></textarea>

</div>

<button
type="submit"
name="submit_feedback"
class="btn btn-success">

Submit Feedback

</button>

<a href="dashboard.php" class="btn btn-secondary">

Cancel

</a>

</form>

</div>

</div>

</div>

</body>

</html>