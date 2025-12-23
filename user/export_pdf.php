<?php
session_start();
include "../config/koneksi.php";

if (
  !isset($_SESSION['status_karyawan']) ||
  !in_array($_SESSION['status_karyawan'], ['magang','kerja_harian'])
) {
  header("Location: ../auth/login.php");
  exit;
}
require_once "../fpdf/fpdf.php";

$user_id = $_SESSION['id'];

$pdf = new FPDF('L','mm','A4');
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,'Riwayat Absensi & Log Book',0,1,'C');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(30,8,'Tanggal',1);
$pdf->Cell(30,8,'Masuk',1);
$pdf->Cell(30,8,'Pulang',1);
$pdf->Cell(180,8,'Log Book',1);
$pdf->Ln();

$pdf->SetFont('Arial','',9);

$q = mysqli_query($connect,"
SELECT 
  a.tanggal,
  a.jam_masuk,
  a.jam_pulang,
  l.aktivitas
FROM absensi a
LEFT JOIN logbook l 
  ON a.user_id = l.user_id 
  AND a.tanggal = l.tanggal
WHERE a.user_id = '$user_id'
ORDER BY a.tanggal DESC
");

while($d = mysqli_fetch_assoc($q)){
  $pdf->Cell(30,8,$d['tanggal'],1);
  $pdf->Cell(30,8,$d['jam_masuk'],1);
  $pdf->Cell(30,8,$d['jam_pulang'],1);
  $pdf->MultiCell(180,8,$d['aktivitas'] ?? '-',1);
}

$pdf->Output();
