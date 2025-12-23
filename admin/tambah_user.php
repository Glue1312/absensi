<?php
session_start();
include "../config/koneksi.php";

// Cek keamanan: Pastikan hanya admin yang bisa akses
if (!isset($_SESSION['status_karyawan']) || $_SESSION['status_karyawan'] != 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

if (isset($_POST['simpan'])) {
  
  // 1. Tetap gunakan MD5 sesuai instruksi Anda
  $password = md5($_POST['password']);

  // 2. Gunakan Prepared Statement (Mencegah SQL Injection)
  // Tanda tanya (?) adalah placeholder agar input user aman
  $stmt = $connect->prepare("
    INSERT INTO users (
      nik, nama, departemen, divisi,
      tgl_masuk, end_date, asal_sekolah,
      status_karyawan, phone, alamat,
      email, password
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
  ");

  // Validasi jika prepare gagal
  if (!$stmt) {
    die("Query Error: " . $connect->error);
  }

  // Bind parameter: "ssssssssssss" berarti 12 data bertipe String
  $stmt->bind_param("ssssssssssss", 
    $_POST['nik'], 
    $_POST['nama'], 
    $_POST['departemen'], 
    $_POST['divisi'],
    $_POST['tgl_masuk'], 
    $_POST['end_date'], 
    $_POST['asal_sekolah'],
    $_POST['status_karyawan'], 
    $_POST['phone'], 
    $_POST['alamat'],
    $_POST['email'], 
    $password // Password yang sudah di-MD5
  );

  // Eksekusi query
  if ($stmt->execute()) {
    header("Location: kelola_user.php");
    exit;
  } else {
    echo "Gagal menyimpan data: " . $stmt->error;
  }
}
?>
<form method="post">
  <input name="email" placeholder="Email" required><br>
  <input type="password" name="password" placeholder="Password" required><br>

  <input name="nik" placeholder="NIK" required><br>
  <input name="nama" placeholder="Nama" required><br>
  <input name="departemen" placeholder="Departemen" required><br>
  <input name="divisi" placeholder="Divisi" required><br>
  <input type="date" name="tgl_masuk" required><br>
  <input type="date" name="end_date"><br>
  <input name="asal_sekolah" placeholder="Asal Sekolah"><br>

  <select name="status_karyawan" required>
    <option value="magang">Magang</option>
    <option value="kerja_harian">Kerja Harian</option>
    <option value="admin">Admin</option>
  </select><br>

  <input name="phone" placeholder="No HP"><br>
  <textarea name="alamat" placeholder="Alamat"></textarea><br>


  <button name="simpan">Simpan</button>
</form>
<a href="kelola_user.php">← Kembali</a>