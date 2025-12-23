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
?>

<h2>Riwayat Absensi & Log Book</h2>

<a href="export_excel.php">⬇ Export Excel</a> | 
<a href="export_pdf.php">⬇ Export PDF</a>
<br><br>

<table border="1" cellpadding="5">
<tr>
  <th>Tanggal</th>
  <th>Jam Masuk</th>
  <th>Jam Pulang</th>
  <th>Log Book</th>
</tr>

<?php while($d = mysqli_fetch_assoc($q)): ?>
<tr>
  <td><?= $d['tanggal'] ?></td>
  <td><?= $d['jam_masuk'] ?></td>
  <td><?= $d['jam_pulang'] ?></td>
  <td><?= nl2br($d['aktivitas'] ?? '-') ?></td>
</tr>
<?php endwhile; ?>
</table>

<br>
<a href="home_user.php">← Kembali</a>
