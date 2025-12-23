<?php
session_start();
include "../config/koneksi.php";

$sekolah=$_SESSION['asal_sekolah'];
$q=mysqli_query($connect,"
SELECT u.nama,a.tanggal,a.jam_masuk,a.jam_pulang
FROM absensi a JOIN users u ON u.id=a.user_id
WHERE u.asal_sekolah='$sekolah'
");
while($d=mysqli_fetch_assoc($q)){
echo "$d[nama] | $d[tanggal] | $d[jam_masuk] - $d[jam_pulang]<br>";
}
