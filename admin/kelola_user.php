<?php
include "../config/koneksi.php";
$q = mysqli_query($connect, "SELECT * FROM users");
?>
<h3>Data User</h3>
<a href="tambah_user.php">+ Tambah User</a><br><br>

<table border="1">
    <tr>
        <th>NAMA</th>
        <th>DEPARTEMEN</th>
        <th>DIVISI FIX</th>
        <th>TANGGAL MASUK</th>
        <th>END DATE</th>
        <th>NIK</th>
        <th>SEKOLAH</th>
        <th>STATUS KARYAWAN</th>
        <th>Aksi</th>
    </tr>
    <?php while ($u = mysqli_fetch_assoc($q)): ?>
    <tr>
        <td><?= $u['nama'] ?></td>
        <td><?= $u['departemen'] ?></td>
        <td><?= $u['divisi'] ?></td>
        <td>
            <?= !empty($u['tgl_masuk']) 
      ? date('d F Y', strtotime($u['tgl_masuk'])) 
      : '-' ?>
        </td>

        <td>
            <?= !empty($u['end_date']) 
      ? date('d F Y', strtotime($u['end_date'])) 
      : '-' ?>
        </td>


        <td><?= $u['nik'] ?></td>
        <td><?= $u['asal_sekolah'] ?></td>
        <td><?= $u['status_karyawan'] ?></td>
        <td>
            <a href="detail_user.php?id=<?= $u['id'] ?>&from=kelola">Detail</a> |
            <a href="edit_user.php?id=<?= $u['id'] ?>">Edit</a> |
            <a href="hapus_user.php?id=<?= $u['id'] ?>" onclick="return confirm('Hapus user?')">Hapus</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<a href="home_admin.php">← Kembali</a>