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

require('../fpdf/fpdf.php');

$pdf = new FPDF();

$pdf->AddPage();

$pdf->SetFont('Arial','B',16);

$pdf->Cell(190,10,'IT HelpDesk Ticket Report',0,1,'C');

$pdf->Ln(5);

$pdf->SetFont('Arial','B',10);

$pdf->Cell(25,10,'Ticket No',1);
$pdf->Cell(45,10,'Title',1);
$pdf->Cell(30,10,'Priority',1);
$pdf->Cell(30,10,'Status',1);
$pdf->Cell(60,10,'Assigned To',1);

$pdf->Ln();

$query = "
SELECT
tickets.TicketNumber,
tickets.Title,
priorities.PriorityName,
statuses.StatusName,
users.FirstName

FROM tickets

LEFT JOIN priorities
ON tickets.PriorityId = priorities.ID

LEFT JOIN statuses
ON tickets.StatusId = statuses.ID

LEFT JOIN users
ON tickets.AssignedTo = users.ID

ORDER BY tickets.ID DESC
";

$result = mysqli_query($conn,$query);

$pdf->SetFont('Arial','',9);

while($row=mysqli_fetch_assoc($result))
{

$pdf->Cell(25,10,$row['TicketNumber'],1);

$pdf->Cell(45,10,substr($row['Title'],0,20),1);

$pdf->Cell(30,10,$row['PriorityName'],1);

$pdf->Cell(30,10,$row['StatusName'],1);

$pdf->Cell(60,10,$row['FirstName'],1);

$pdf->Ln();

}

$pdf->Output();

?>