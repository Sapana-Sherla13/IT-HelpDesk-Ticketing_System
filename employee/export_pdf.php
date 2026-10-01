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

require('../fpdf/fpdf.php');

$userId = $_SESSION['user_id'];

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn,$_GET['search']) : "";
$status = isset($_GET['status']) ? mysqli_real_escape_string($conn,$_GET['status']) : "";
$priority = isset($_GET['priority']) ? mysqli_real_escape_string($conn,$_GET['priority']) : "";
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn,$_GET['category']) : "";

$query = "

SELECT

tickets.TicketNumber,
tickets.Title,
categories.CategoryName,
priorities.PriorityName,
statuses.StatusName,
tickets.CreatedDate

FROM tickets

LEFT JOIN categories
ON tickets.CategoryId=categories.ID

LEFT JOIN priorities
ON tickets.PriorityId=priorities.ID

LEFT JOIN statuses
ON tickets.StatusId=statuses.ID

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

$query .= " ORDER BY tickets.ID DESC ";

$result = mysqli_query($conn,$query);

$pdf = new FPDF();

$pdf->AddPage();

$pdf->SetFont('Arial','B',16);

$pdf->Cell(190,10,'My Tickets Report',0,1,'C');

$pdf->Ln(5);

$pdf->SetFont('Arial','B',10);

$pdf->Cell(25,10,'Ticket',1);
$pdf->Cell(55,10,'Title',1);
$pdf->Cell(30,10,'Category',1);
$pdf->Cell(25,10,'Priority',1);
$pdf->Cell(25,10,'Status',1);
$pdf->Cell(30,10,'Date',1);

$pdf->Ln();

$pdf->SetFont('Arial','',9);

while($row=mysqli_fetch_assoc($result))
{
    $pdf->Cell(25,10,$row['TicketNumber'],1);
    $pdf->Cell(55,10,substr($row['Title'],0,25),1);
    $pdf->Cell(30,10,$row['CategoryName'],1);
    $pdf->Cell(25,10,$row['PriorityName'],1);
    $pdf->Cell(25,10,$row['StatusName'],1);
    $pdf->Cell(30,10,date("d-m-Y",strtotime($row['CreatedDate'])),1);

    $pdf->Ln();
}

$pdf->Output("D","MyTickets.pdf");
exit();

?>