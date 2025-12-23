<?php
session_start();
include "../config/koneksi.php";

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
   HEADER EXCEL
========================= */
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=rekap_absensi.xls");

echo "Nama\tstatus_karyawan\tSekolah\tdivisi\tTanggal\tMasuk\tPulang\n";

/* =========================
   QUERY DATA
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
  echo
    "$d[nama]\t$d[status_karyawan]\t$d[asal_sekolah]\t$d[divisi]\t".
    "$d[tanggal]\t$d[jam_masuk]\t$d[jam_pulang]\n";
}
