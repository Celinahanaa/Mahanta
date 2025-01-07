<?php
// like.php
session_start();
require 'koneksi.php';

if (!empty($_SESSION["id"])) {
    $userId = $_SESSION["id"];
    $imgSrc = $_POST['imgSrc'] ?? null;
    $liked = filter_var($_POST['liked'], FILTER_VALIDATE_BOOLEAN);

    if ($imgSrc) {
        // Ambil ID gambar berdasarkan src
        $imgFileName = basename($imgSrc); // Simpan basename ke dalam variabel
        $query = "SELECT id FROM gambar WHERE gambar = ?"; // Pastikan gambar dicocokkan dengan benar
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("s", $imgFileName); // Gunakan variabel untuk bind_param
        $stmt->execute();
        $result = $stmt->get_result();
        $gambar = $result->fetch_assoc();

        if ($gambar) {
            $gambarId = $gambar['id'];

            if ($liked) {
                // Tambahkan data like
                $insertQuery = "INSERT IGNORE INTO likes (user_id, gambar_id) VALUES (?, ?)";
                $stmt = $koneksi->prepare($insertQuery);
                $stmt->bind_param("ii", $userId, $gambarId);
                $stmt->execute();
            } else {
                // Hapus data like
                $deleteQuery = "DELETE FROM likes WHERE user_id = ? AND gambar_id = ?";
                $stmt = $koneksi->prepare($deleteQuery);
                $stmt->bind_param("ii", $userId, $gambarId);
                $stmt->execute();
            }

            // Hitung jumlah like terbaru
            $countQuery = "SELECT COUNT(*) AS like_count FROM likes WHERE gambar_id = ?";
            $stmt = $koneksi->prepare($countQuery);
            $stmt->bind_param("i", $gambarId);
            $stmt->execute();
            $result = $stmt->get_result();
            $likeCount = $result->fetch_assoc()['like_count'];

            echo json_encode(['success' => true, 'like_count' => $likeCount]);
            exit();
        } else {
            echo json_encode(['success' => false, 'message' => 'Image not found']);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid image source']);
        exit();
    }
}

echo json_encode(['success' => false, 'message' => 'User not logged in']);
?>
