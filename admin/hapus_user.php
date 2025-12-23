<?php
session_start(); // 1. Mulai sesi
include "../config/koneksi.php";

// 2. Cek apakah user adalah ADMIN
if (!isset($_SESSION['status_karyawan']) || $_SESSION['status_karyawan'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// 3. Validasi ID (pastikan angka)
$id = (int) $_GET['id'];

// 4. Hapus data (sebaiknya gunakan prepared statement juga di sini)
$stmt = $connect->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: kelola_user.php");
?>