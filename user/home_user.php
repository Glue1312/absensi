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
$tanggal = date("Y-m-d");

// Ambil data user
$user = mysqli_fetch_assoc(
  mysqli_query($connect,"
    SELECT 
      nama, 
      departemen, 
      divisi, 
      status_karyawan
    FROM users 
    WHERE id='$user_id'
  ")
);
// Cek status absensi hari ini (untuk info)
$q = mysqli_query($connect,"
  SELECT jam_masuk, jam_pulang
  FROM absensi
  WHERE user_id='$user_id' AND tanggal='$tanggal'
  LIMIT 1
");

$status = "Belum absen";
$labelTombol = "📍 Absen";

if ($a = mysqli_fetch_assoc($q)) {
  if ($a['jam_pulang']) {
    $status = "Sudah absen masuk & pulang";
    $labelTombol = "✅ Absen Selesai";
  } else {
    $status = "Sudah absen masuk";
    $labelTombol = "📍 Absen Pulang";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Home User</title>
</head>
<body>

<h2>Dashboard User</h2>

<div style="border:1px solid #ccc; padding:10px; width:350px;">
  <p><b>Nama:</b> <?= htmlspecialchars($user['nama']) ?></p>
  <p><b>Departemen:</b> <?= htmlspecialchars($user['departemen']) ?></p>
  <p><b>Divisi:</b> <?= htmlspecialchars($user['divisi']) ?></p>
  <p><b>Status:</b> <?= ucfirst($user['status_karyawan']) ?></p>
</div>

<hr>

<p><b>Status hari ini:</b> <?= $status ?></p>

<!-- TOMBOL ABSEN LANGSUNG -->
<button id="btnAbsen"
  onclick="absen()"
  <?= ($status == 'Sudah absen masuk & pulang') ? 'disabled' : '' ?>
>
  <?= $labelTombol ?>
</button>

<p id="info"></p>

<hr>

<a href="logbook.php">📝 Log Book Harian</a><br>
<a href="riwayat.php">📊 Riwayat Absensi</a><br>
<a href="../auth/logout.php">🚪 Logout</a>

<script>
function absen() {
  if (!navigator.geolocation) {
    alert("Browser tidak mendukung GPS");
    return;
  }

  document.getElementById("info").innerText = "📡 Mengambil lokasi...";

  navigator.geolocation.getCurrentPosition(
    function(pos) {
      fetch("proses_absen.php", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({
          lat: pos.coords.latitude,
          lng: pos.coords.longitude
        })
      })
      .then(res => res.text())
      .then(res => {
        document.getElementById("info").innerText = res;

        // Refresh halaman agar status & tombol update
        setTimeout(() => location.reload(), 1000);
      });
    },
    function(err) {
      alert("Gagal mengambil lokasi: " + err.message);
    },
    { enableHighAccuracy: true }
  );
}
</script>

</body>
</html>
