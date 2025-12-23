<?php
include "../config/koneksi.php";
$q = mysqli_query($connect,"
SELECT users.nama, absensi.*
FROM absensi JOIN users ON users.id=absensi.user_id");
?>
<table border="1">
<tr><th>Nama</th><th>Tanggal</th><th>Jam</th><th>Jarak</th></tr>
<?php while($d=mysqli_fetch_assoc($q)){ ?>
<tr>
<td><?= $d['nama'] ?></td>
<td><?= $d['tanggal'] ?></td>
<td><?= $d['jam_masuk'] ?></td>
<td><?= round($d['jarak'],1) ?> m</td>
</tr>
<?php } ?>
</table>
