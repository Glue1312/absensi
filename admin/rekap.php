<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['status_karyawan']) || $_SESSION['status_karyawan'] != 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

// ================= FILTER =================
$where = "1=1";

if (!empty($_GET['nama'])) {
  $nama = mysqli_real_escape_string($connect, $_GET['nama']);
  $where .= " AND u.nama LIKE '%$nama%'";
}

if (!empty($_GET['bulan'])) {
  $bulan = $_GET['bulan'];
  $where .= " AND DATE_FORMAT(a.tanggal,'%Y-%m')='$bulan'";
}

if (!empty($_GET['asal_sekolah'])) {
  $sekolah = mysqli_real_escape_string($connect, $_GET['asal_sekolah']);
  $where .= " AND u.asal_sekolah LIKE '%$sekolah%'";
}

if (!empty($_GET['status_karyawan'])) {
  $status_karyawan = $_GET['status_karyawan'];
  $where .= " AND u.status_karyawan='$status_karyawan'";
}

if (!empty($_GET['divisi'])) {
  $divisi = mysqli_real_escape_string($connect, $_GET['divisi']);
  $where .= " AND u.divisi LIKE '%$divisi'";
}


// untuk export
$queryString = $_SERVER['QUERY_STRING'];

// ================= QUERY =================
$q = mysqli_query($connect,"
SELECT 
  u.id AS user_id,
  u.nama,
  u.status_karyawan,
  u.asal_sekolah,
  u.divisi,
  a.tanggal,
  a.jam_masuk,
  a.jam_pulang
FROM absensi a
JOIN users u ON u.id = a.user_id
WHERE $where
ORDER BY a.tanggal DESC
");
?>

<h2>Rekap Absensi</h2>

<a href="export_excel.php?<?= $queryString ?>">⬇ Export Excel</a> |
<a href="export_pdf.php?<?= $queryString ?>">⬇ Export PDF</a>

<br><br>

<form method="get">
Nama:
<input name="nama" value="<?= $_GET['nama'] ?? '' ?>">

Bulan:
<input type="month" name="bulan" value="<?= $_GET['bulan'] ?? '' ?>">

Sekolah:
<input name="asal_sekolah" value="<?= $_GET['asal_sekolah'] ?? '' ?>">

status_karyawan:
<select name="status_karyawan">
  <option value="">Semua</option>
  <option value="magang" <?= ($_GET['status_karyawan'] ?? '')=='magang'?'selected':'' ?>>Magang</option>
  <option value="kerja_harian" <?= ($_GET['status_karyawan'] ?? '')=='kerja_harian'?'selected':'' ?>>Kerja Harian</option>
</select>

status_karyawan:
<input name="divisi" value="<?= $_GET['divisi'] ?? '' ?>">

<button>Filter</button>
</form>

<br>

<table border="1" cellpadding="5">
<tr>
  <th>Nama</th>
  <th>Divisi</th>
  <th>Tanggal</th>
  <th>Masuk</th>
  <th>Pulang</th>
  <th>Sekolah</th>
  <th>Status Karyawan</th>
  <th>Aksi</th>
</tr>

<?php while ($d = mysqli_fetch_assoc($q)): ?>
<tr>
  <td><?= htmlspecialchars($d['nama']) ?></td>
  <td><?= $d['divisi'] ?></td>
  <td><?= $d['tanggal'] ?></td>
  <td><?= $d['jam_masuk'] ?></td>
  <td><?= $d['jam_pulang'] ?></td>
  <td><?= $d['asal_sekolah'] ?></td>
  <td><?= $d['status_karyawan'] ?></td>
  <td>
    <a href="detail_user.php?id=<?= $d['user_id'] ?>&from=recap">🔍 Detail</a>
  </td>
</tr>
<?php endwhile; ?>
</table>

<br>
<a href="home_admin.php">← Kembali</a>
