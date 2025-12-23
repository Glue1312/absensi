<?php
session_start();
include "../config/koneksi.php";

$email = mysqli_real_escape_string($connect, $_POST['email']);
$password = md5($_POST['password']);

$q = mysqli_query($connect,"
  SELECT * FROM users 
  WHERE email='$email' 
  AND password='$password'
  LIMIT 1
");

if (mysqli_num_rows($q) == 1) {

  $u = mysqli_fetch_assoc($q);

  // 🔐 Security
  session_regenerate_id(true);

  $_SESSION['id'] = $u['id'];
  $_SESSION['status_karyawan'] = $u['status_karyawan'];

  if ($u['status_karyawan'] == 'admin') {
    header("Location: ../admin/home_admin.php");
  } else {
    header("Location: ../user/home_user.php");
  }
  exit;
}

echo "❌ Email atau password salah";
