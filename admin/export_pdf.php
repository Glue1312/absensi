<?php
session_start();
include "../config/koneksi.php";
require_once "../fpdf/fpdf.php";

if ($_SESSION['status_karyawan'] != 'admin') {
  exit("Akses ditolak");
}

/* =========================
   FILTER (SAMA DENGAN REKAP)
========================= */
$where = "1=1";

if (!empty($_GET['nama'])) {
  $nama = mysqli_real_escape_string($connect,$_GET['nama']);
  $where .= " AND u.nama LIKE '%$nama%'";
}

if (!empty($_GET['bulan'])) {
  $bulan = $_GET['bulan'];
  $where .= " AND DATE_FORMAT(a.tanggal,'%Y-%m')='$bulan'";
}

if (!empty($_GET['asal_sekolah'])) {
  $sekolah = mysqli_real_escape_string($connect,$_GET['asal_sekolah']);
  $where .= " AND u.asal_sekolah LIKE '%$sekolah%'";
}

if (!empty($_GET['status_karyawan'])) {
  $status_karyawan = $_GET['status_karyawan'];
  $where .= " AND u.status_karyawan='$status_karyawan'";
}

if (!empty($_GET['divisi'])) {
  $divisi = mysqli_real_escape_string($connect,$_GET['divisi']);
  $where .= " AND u.divisi LIKE '%$divisi%'";
}

/* =========================
   PDF SETUP
========================= */
$pdf = new FPDF('L','mm','A4');
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,'Rekap Absensi',0,1,'C');

$pdf->Ln(3);
$pdf->SetFont('Arial','B',9);

$pdf->Cell(35,8,'Nama',1);
$pdf->Cell(30,8,'status_karyawan',1);
$pdf->Cell(40,8,'Sekolah',1);
$pdf->Cell(35,8,'divisi',1);
$pdf->Cell(25,8,'Tanggal',1);
$pdf->Cell(25,8,'Masuk',1);
$pdf->Cell(25,8,'Pulang',1);
$pdf->Ln();

$pdf->SetFont('Arial','',8);

/* =========================
   DATA
========================= */
$q = mysqli_query($connect,"
SELECT 
  u.nama,
  u.status_karyawan,
  u.asal_sekolah,
  u.divisi,
  a.tanggal,
  a.jam_masuk,
  a.jam_pulang
FROM absensi a
JOIN users u ON u.id=a.user_id
WHERE $where
ORDER BY a.tanggal DESC
");

while ($d = mysqli_fetch_assoc($q)) {
  $pdf->Cell(35,8,$d['nama'],1);
  $pdf->Cell(30,8,$d['status_karyawan'],1);
  $pdf->Cell(40,8,$d['asal_sekolah'],1);
  $pdf->Cell(35,8,$d['divisi'],1);
  $pdf->Cell(25,8,$d['tanggal'],1);
  $pdf->Cell(25,8,$d['jam_masuk'],1);
  $pdf->Cell(25,8,$d['jam_pulang'],1);
  $pdf->Ln();
}

$pdf->Output();
