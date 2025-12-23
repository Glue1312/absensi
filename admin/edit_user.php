<?php
session_start();
include "../config/koneksi.php";

// =======================
// PROTEKSI ADMIN
// =======================
if (!isset($_SESSION['status_karyawan']) || $_SESSION['status_karyawan'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// =======================
// VALIDASI ID
// =======================
if (!isset($_GET['id'])) {
    header("Location: kelola_user.php");
    exit;
}

$id = $_GET['id'];

// =======================
// AMBIL DATA USER
// =======================
$result = mysqli_query($connect, "SELECT * FROM users WHERE id='$id'");
$user = mysqli_fetch_assoc($result);

if (!$user) {
    header("Location: kelola_user.php");
    exit;
}

// =======================
// PROSES UPDATE
// =======================
if (isset($_POST['update'])) {

    $nama            = $_POST['nama'];
    $email           = $_POST['email'];
    $status_karyawan = $_POST['status_karyawan'];
    $asal_sekolah    = $_POST['asal_sekolah'];
    $divisi      = $_POST['divisi'];
    $nik             = $_POST['nik'];
    $departemen      = $_POST['departemen'];
    $divisi          = $_POST['divisi'];
    $phone           = $_POST['phone'];
    $alamat          = $_POST['alamat'];

    // HANDLE TANGGAL (BOLEH NULL)
    $tgl_masuk = !empty($_POST['tgl_masuk']) ? $_POST['tgl_masuk'] : NULL;
    $end_date  = !empty($_POST['end_date'])  ? $_POST['end_date']  : NULL;

    // QUERY UPDATE (FINAL)
    $query = "
        UPDATE users SET
            nama='$nama',
            email='$email',
            status_karyawan='$status_karyawan',
            asal_sekolah='$asal_sekolah',
            divisi='$divisi',
            nik='$nik',
            departemen='$departemen',
            divisi='$divisi',
            tgl_masuk=" . ($tgl_masuk ? "'$tgl_masuk'" : "NULL") . ",
            end_date=" . ($end_date ? "'$end_date'" : "NULL") . ",
            phone='$phone',
            alamat='$alamat'
        WHERE id='$id'
    ";

    $update = mysqli_query($connect, $query);

    // DEBUG JIKA GAGAL
    if (!$update) {
        die("Gagal update data: " . mysqli_error($connect));
    }

    header("Location: kelola_user.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
</head>
<body>

<h2>Edit User</h2>

<form method="post">
    <input name="nama" value="<?= htmlspecialchars($user['nama']) ?>" required><br>
    <input name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br>
    <input name="nik" value="<?= htmlspecialchars($user['nik']) ?>"><br>
    <input name="departemen" value="<?= htmlspecialchars($user['departemen']) ?>"><br>
    <input name="divisi" value="<?= htmlspecialchars($user['divisi']) ?>"><br>

    <input type="date" name="tgl_masuk"
           value="<?= !empty($user['tgl_masuk']) ? date('Y-m-d', strtotime($user['tgl_masuk'])) : '' ?>"><br>

    <input type="date" name="end_date"
           value="<?= !empty($user['end_date']) ? date('Y-m-d', strtotime($user['end_date'])) : '' ?>"><br>

    <select name="status_karyawan" required>
        <option value="magang" <?= $user['status_karyawan'] == 'magang' ? 'selected' : '' ?>>Magang</option>
        <option value="kerja_harian" <?= $user['status_karyawan'] == 'kerja_harian' ? 'selected' : '' ?>>Kerja Harian</option>
        <option value="admin" <?= $user['status_karyawan'] == 'admin' ? 'selected' : '' ?>>Admin</option>
    </select><br>

    <input name="asal_sekolah" value="<?= htmlspecialchars($user['asal_sekolah']) ?>"><br>
    <input name="phone" value="<?= htmlspecialchars($user['phone']) ?>"><br>
    <input name="alamat" value="<?= htmlspecialchars($user['alamat']) ?>"><br>

    <button name="update">Update</button>
</form>

<br>
<a href="kelola_user.php">← Kembali</a>

</body>
</html>
