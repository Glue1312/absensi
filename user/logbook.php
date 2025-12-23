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
date_default_timezone_set("Asia/Jakarta");

$user_id = $_SESSION['id'];
$tanggal = date("Y-m-d");

/* =========================
   CEK ABSENSI HARI INI
========================= */
$qAbsen = mysqli_query($connect,"
  SELECT * FROM absensi
  WHERE user_id='$user_id' AND tanggal='$tanggal'
  LIMIT 1
");

if (mysqli_num_rows($qAbsen) == 0) {
  echo "❌ Anda belum melakukan absen masuk hari ini.";
  echo "<br><a href='home_user.php'>← Kembali</a>";
  exit;
}

$absen = mysqli_fetch_assoc($qAbsen);
$sudahPulang = !is_null($absen['jam_pulang']);

/* =========================
   CEK LOGBOOK HARI INI
========================= */
$qLog = mysqli_query($connect,"
  SELECT * FROM logbook
  WHERE user_id='$user_id' AND tanggal='$tanggal'
  LIMIT 1
");

$logbook = mysqli_fetch_assoc($qLog);

/* =========================
   SIMPAN / UPDATE LOGBOOK
========================= */
if (isset($_POST['simpan'])) {

  if ($sudahPulang) {
    echo "❌ Log book tidak dapat diubah setelah absen pulang.";
    exit;
  }

  $aktivitas = mysqli_real_escape_string($connect, $_POST['aktivitas']);

  if ($logbook) {
    // UPDATE
    mysqli_query($connect,"
      UPDATE logbook
      SET aktivitas='$aktivitas'
      WHERE id='{$logbook['id']}'
    ");
    echo "✅ Log book berhasil diperbarui";
  } else {
    // INSERT
    mysqli_query($connect,"
      INSERT INTO logbook (user_id,tanggal,aktivitas)
      VALUES ('$user_id','$tanggal','$aktivitas')
    ");
    echo "✅ Log book berhasil disimpan";
  }

  echo "<br><a href='logbook.php'>Refresh</a>";
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Log Book Harian</title>
</head>
<body>

<h2>Log Book Harian</h2>
<p><b>Tanggal:</b> <?= $tanggal ?></p>

<?php if ($sudahPulang): ?>
  <!-- MODE READ ONLY -->
  <p><b>Status:</b> Sudah absen pulang (log book terkunci)</p>
  <textarea rows="6" cols="60" disabled><?= $logbook['aktivitas'] ?? '' ?></textarea>

<?php else: ?>
  <!-- MODE TAMBAH / EDIT -->
  <form method="post">
    <textarea name="aktivitas" rows="6" cols="60" required><?= $logbook['aktivitas'] ?? '' ?></textarea><br><br>
    <button name="simpan">
      <?= $logbook ? '💾 Update Log Book' : '💾 Simpan Log Book' ?>
    </button>
  </form>
<?php endif; ?>

<br><br>
<a href="home_user.php">← Kembali</a>

</body>
</html>
