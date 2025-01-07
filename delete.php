<?php
session_start();
require 'koneksi.php';

// Periksa apakah parameter ID dikirim melalui URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $art_id = intval($_GET['id']); // Konversi ke integer untuk keamanan

    // Periksa apakah data dengan ID tersebut ada di database
    $check_query = "SELECT * FROM gambar WHERE id = ?";
    $stmt_check = $koneksi->prepare($check_query);
    $stmt_check->bind_param("i", $art_id);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        // Hapus data yang terkait di tabel 'likes' terlebih dahulu
$delete_likes_query = "DELETE FROM likes WHERE gambar_id = ?";
$stmt_delete_likes = $koneksi->prepare($delete_likes_query);
$stmt_delete_likes->bind_param("i", $art_id);
$stmt_delete_likes->execute();
$stmt_delete_likes->close();

// Kemudian hapus data di tabel 'gambar'
$delete_query = "DELETE FROM gambar WHERE id = ?";
$stmt_delete = $koneksi->prepare($delete_query);
$stmt_delete->bind_param("i", $art_id);

if ($stmt_delete->execute()) {
    // Redirect ke halaman utama dengan status berhasil
    header("Location: repart.php?status=deleted");
    exit;
} else {
    // Jika penghapusan gagal
    echo "<p class='alert alert-danger'>Terjadi kesalahan saat menghapus data. Silakan coba lagi.</p>";
}

$stmt_delete->close();
    } else {
        // Jika data dengan ID tersebut tidak ditemukan
        echo "<p class='alert alert-warning'>Data tidak ditemukan.</p>";
    }

    $stmt_check->close();
} else {
    // Jika parameter ID tidak valid atau tidak dikirim
    echo "<p class='alert alert-danger'>ID tidak valid atau tidak tersedia.</p>";
}

$koneksi->close();
?>
