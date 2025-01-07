<?php
session_start();
require 'koneksi.php';

// Periksa apakah ID kategori ada di URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus kategori berdasarkan ID
    $sql = "DELETE FROM kategori WHERE id = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Setelah berhasil dihapus, arahkan kembali ke halaman kategori
        header('Location: repcategory.php');
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "ID tidak ditemukan!";
}
?>
