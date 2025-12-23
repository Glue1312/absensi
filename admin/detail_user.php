<?php
session_start();
include "../config/koneksi.php";

// 🔐 Proteksi admin
if (!isset($_SESSION['status_karyawan']) || $_SESSION['status_karyawan'] != 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

// Validasi parameter id
if (!isset($_GET['id'])) {
  header("Location: rekap.php");
  exit;
}

$id = $_GET['id'];

// Ambil data user
$user = mysqli_fetch_assoc(
  mysqli_query($connect,"SELECT * FROM users WHERE id='$id'")
);

if (!$user) {
  header("Location: rekap.php");
  exit;
}

$from = $_GET['from'] ?? 'rekap';

switch ($from) {
  case 'kelola':
    $back_url = 'kelola_user.php';
    $back_text = '← Kembali ke Kelola User';
    break;

  case 'rekap':
  default:
    $back_url = 'rekap.php';
    $back_text = '← Kembali ke Rekap';
    break;
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Detail User</title>
</head>
<body>

<h2>Detail User</h2>
<p>
  <b>Nama:</b> <?= htmlspecialchars($user['nama']) ?><br>
  <b>NIK:</b> <?= $user['nik'] ?><br>
  <b>departemen:</b> <?= $user['departemen'] ?><br>
  <b>divisi:</b> <?= $user['divisi'] ?><br>
  <b>tanggal masuk:</b> <?= $user['tgl_masuk'] ?><br>
  <b>tanggal selesai:</b> <?= $user['end_date'] ?><br>
  <b>status karyawan:</b> <?= $user['status_karyawan'] ?><br>
  <b>Sekolah:</b> <?= $user['asal_sekolah'] ?><br>
  <b>phone:</b> <?= $user['phone'] ?><br>
  <b>alamat:</b> <?= $user['alamat'] ?><br>
</p>

<hr>

<h3>Log Book Harian</h3>

<table border="1" cellpadding="5">
<tr>
  <th>Tanggal</th>
  <th>Aktivitas</th>
</tr>

<?php
$qLog = mysqli_query($connect,"
  SELECT tanggal, aktivitas
  FROM logbook
  WHERE user_id='$id'
  ORDER BY tanggal DESC
");

if (mysqli_num_rows($qLog) == 0) {
  echo "<tr><td colspan='2'>Belum ada logbook</td></tr>";
}

while ($l = mysqli_fetch_assoc($qLog)):
?>
<tr>
  <td><?= $l['tanggal'] ?></td>
  <td><?= nl2br(htmlspecialchars($l['aktivitas'])) ?></td>
</tr>
<?php endwhile; ?>
</table>

<hr>

<h3>Riwayat Absensi</h3>

<table border="1" cellpadding="5">
<tr>
  <th>Tanggal</th>
  <th>Masuk</th>
  <th>Pulang</th>
</tr>

<?php
$qAbs = mysqli_query($connect,"
  SELECT tanggal, jam_masuk, jam_pulang
  FROM absensi
  WHERE user_id='$id'
  ORDER BY tanggal DESC
");

while ($a = mysqli_fetch_assoc($qAbs)):
?>
<tr>
  <td><?= $a['tanggal'] ?></td>
  <td><?= $a['jam_masuk'] ?></td>
  <td><?= $a['jam_pulang'] ?></td>
</tr>
<?php endwhile; ?>
</table>

<br>
<a href="<?= $back_url ?>"><?= $back_text ?></a>



</body>
</html>
