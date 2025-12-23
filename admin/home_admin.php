<?php
session_start();
if($_SESSION['status_karyawan']!='admin') header("Location: ../auth/login.php");
?>
<h2>Admin</h2>
<a href="kelola_user.php">Kelola User</a><br>
<a href="rekap.php">Rekap Absensi</a><br>
<a href="logbook.php">📘 Data Log Book</a><br>

<a href="../auth/logout.php">Logout</a>
