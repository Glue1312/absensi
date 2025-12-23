<?php
session_start();
include "../config/koneksi.php";

/* =============================
   PROTEKSI USER
============================= */
if (
    !isset($_SESSION['status_karyawan']) ||
    !in_array($_SESSION['status_karyawan'], ['magang', 'kerja_harian'])
) {
    header("Location: ../auth/login.php");
    exit;
}

date_default_timezone_set("Asia/Jakarta");

/* =============================
   AMBIL DATA LOKASI (JSON)
============================= */
$data = json_decode(file_get_contents("php://input"), true);

$lat = isset($data['lat']) ? floatval($data['lat']) : null;
$lng = isset($data['lng']) ? floatval($data['lng']) : null;

if ($lat === null || $lng === null) {
    echo "❌ Lokasi tidak valid";
    exit;
}

/* =============================
   KONFIGURASI KANTOR
============================= */
$latK   = -7.691556241949032;
$lngK   = 110.6236385554015;
$radius = 300; // meter (realistis)

/* =============================
   FUNGSI HITUNG JARAK (HAVERSINE)
============================= */
function hitungJarak($lat1, $lon1, $lat2, $lon2)
{
    $R = 6371000; // meter
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon / 2) * sin($dLon / 2);

    return $R * (2 * atan2(sqrt($a), sqrt(1 - $a)));
}

/* =============================
   VALIDASI JARAK
============================= */
$jarak = hitungJarak($lat, $lng, $latK, $lngK);

if ($jarak > $radius) {
    echo "❌ Anda di luar area kantor (jarak: " . round($jarak) . " meter)";
    exit;
}

/* =============================
   DATA DASAR ABSENSI
============================= */
$user_id = $_SESSION['id'];
$tanggal = date("Y-m-d");
$jam     = date("H:i:s");

/* =============================
   CEK ABSENSI HARI INI
============================= */
$qAbsensi = mysqli_query($connect, "
    SELECT * FROM absensi
    WHERE user_id='$user_id' AND tanggal='$tanggal'
    LIMIT 1
");

/* ==================================================
   KASUS 1: BELUM ABSEN → ABSEN MASUK
================================================== */
if (mysqli_num_rows($qAbsensi) === 0) {

    mysqli_query($connect, "
        INSERT INTO absensi
        (user_id, tanggal, jam_masuk, latitude, longitude, jarak)
        VALUES
        ('$user_id', '$tanggal', '$jam', '$lat', '$lng', '$jarak')
    ");

    echo "✅ Absen masuk berhasil";
    exit;
}

/* ==================================================
   DATA ABSEN
================================================== */
$absen = mysqli_fetch_assoc($qAbsensi);

/* === SUDAH ABSEN PULANG === */
if (!is_null($absen['jam_pulang'])) {
    echo "⚠️ Anda sudah absen masuk dan pulang hari ini";
    exit;
}

/* ==================================================
   VALIDASI LOGBOOK (WAJIB)
================================================== */
$qLog = mysqli_query($connect, "
    SELECT id FROM logbook
    WHERE user_id='$user_id' AND tanggal='$tanggal'
    LIMIT 1
");

if (mysqli_num_rows($qLog) === 0) {
    echo "⚠️ Silakan isi log book hari ini sebelum absen pulang";
    exit;
}

/* ==================================================
   KASUS 2: ABSEN PULANG
================================================== */
mysqli_query($connect, "
    UPDATE absensi
    SET jam_pulang='$jam'
    WHERE id='{$absen['id']}'
");

echo "✅ Absen pulang berhasil";
exit;
