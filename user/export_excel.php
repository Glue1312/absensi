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


$user_id = $_SESSION['id'];

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=riwayat_absensi.xls");

echo "Tanggal\tJam Masuk\tJam Pulang\tLog Book\n";

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
  $aktivitas = str_replace(["\r","\n","\t"], " ", $d['aktivitas']);
  echo "$d[tanggal]\t$d[jam_masuk]\t$d[jam_pulang]\t$aktivitas\n";
}
