<?php
session_start();
require 'koneksi.php';  // Pastikan koneksi ke database sudah benar

// Cek apakah 'imgSrc' ada dalam request
if (isset($_POST['imgSrc'])) {
    $imgSrc = $_POST['imgSrc'];



    // Asumsi kita menggunakan session untuk menyimpan ID user yang sedang login
    if (isset($_SESSION['id'])) {
        $userId = $_SESSION['id'];

        // Ambil ID gambar berdasarkan nama file
        $imgFileName = basename($imgSrc);  // Tanpa urlencoding
        
        $query = "SELECT id FROM gambar WHERE gambar = ?";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("s", $imgFileName); // Gunakan variabel untuk bind_param
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Ambil ID gambar
            $gambar = $result->fetch_assoc();
            $gambarId = $gambar['id'];

            
            // Panggil fungsi untuk memeriksa status like gambar berdasarkan gambarId dan userId
            $result = checkIfLiked($gambarId, $userId);

            if ($result) {
                // Jika gambar sudah di-like
                echo json_encode(['success' => true, 'liked' => true, 'id' => $gambarId]);
            } else {
                // Jika gambar belum di-like
                echo json_encode(['success' => true, 'liked' => false, 'id' => $gambarId]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Image not found', 'id' => null]);
        }
    } else {
        // Jika user belum login
        echo json_encode(['success' => false, 'message' => 'User not logged in']);
    }
} else {
    // Jika imgSrc tidak ada dalam request
    echo json_encode(['success' => false, 'message' => 'No image specified']);
}

// Fungsi untuk memeriksa apakah gambar sudah di-like oleh user
function checkIfLiked($gambarId, $userId) {
    global $koneksi;  // Asumsi koneksi sudah didefinisikan di 'koneksi.php'

    // Query untuk memeriksa apakah user sudah memberi like pada gambar ini
    $query = "SELECT * FROM likes WHERE user_id = ? AND gambar_id = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("ii", $userId, $gambarId);  // Menggunakan parameter untuk mencegah SQL injection
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika ada hasil, berarti gambar sudah di-like oleh user
    return $result->num_rows > 0;
}
?>
