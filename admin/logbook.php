<?php
session_start();
include "../config/koneksi.php";

if ($_SESSION['status_karyawan'] != 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

// FILTER
$where = "1=1";

if (!empty($_GET['nama'])) {
  $nama = mysqli_real_escape_string($connect,$_GET['nama']);
  $where .= " AND u.nama LIKE '%$nama%'";
}

if (!empty($_GET['bulan'])) {
  $bulan = $_GET['bulan'];
  $where .= " AND DATE_FORMAT(l.tanggal,'%Y-%m')='$bulan'";
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

$q = mysqli_query($connect,"
SELECT 
  u.nama,
  u.status_karyawan,
  u.asal_sekolah,
  u.divisi,
  l.tanggal,
  l.aktivitas
FROM logbook l
JOIN users u ON u.id=l.user_id
WHERE $where
ORDER BY l.tanggal DESC
");
?>
<h2>Data Log Book</h2>

<form method="get">
Nama:
<input name="nama">

Bulan:
<input type="month" name="bulan">

Sekolah:
<input name="asal_sekolah">

status_karyawan:
<select name="status_karyawan">
  <option value="">Semua</option>
  <option value="magang">Magang</option>
  <option value="kerja_harian">Kerja Harian</option>
</select>

divisi:
<input name="divisi">

<button>Filter</button>
</form>
<br>
<table border="1" cellpadding="5">
<tr>
  <th>Nama</th>
  <th>status_karyawan</th>
  <th>Sekolah</th>
  <th>divisi</th>
  <th>Tanggal</th>
  <th>Aktivitas</th>
</tr>

<?php while($d=mysqli_fetch_assoc($q)): ?>
<tr>
  <td><?= $d['nama'] ?></td>
  <td><?= $d['status_karyawan'] ?></td>
  <td><?= $d['asal_sekolah'] ?></td>
  <td><?= $d['divisi'] ?></td>
  <td><?= $d['tanggal'] ?></td>
  <td><?= nl2br(htmlspecialchars($d['aktivitas'])) ?></td>
</tr>
<?php endwhile; ?>
</table>

<br>
<a href="home_admin.php">← Kembali</a>
