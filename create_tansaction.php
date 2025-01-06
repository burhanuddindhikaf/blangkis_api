<?php
$host = 'localhost'; // Ganti dengan host database
$username = 'root'; // Ganti dengan username database
$password = ''; // Ganti dengan password database
$dbname = 'appblangkis'; // Ganti dengan nama database

// Koneksi ke database
$conn = new mysqli($host, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek jika request method adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil input dari POST
    $total_bayar = isset($_POST['total_bayar']) ? $_POST['total_bayar'] : null;
    $link_bukti = isset($_POST['link_bukti']) ? $_POST['link_bukti'] : null;

    // Validasi input
    if ($total_bayar && $link_bukti) {
        // Query untuk memasukkan data transaksi
        $sql = "INSERT INTO transaksi (total_bayar, link_bukti) VALUES (?, ?)";

        // Persiapkan statement
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            // Binding parameter
            $stmt->bind_param("is", $total_bayar, $link_bukti);

            // Eksekusi query
            if ($stmt->execute()) {
                echo json_encode(['message' => 'Transaksi berhasil ditambahkan!']);
            } else {
                echo json_encode(['message' => 'Terjadi kesalahan, coba lagi!']);
            }

            // Tutup statement
            $stmt->close();
        } else {
            echo json_encode(['message' => 'Gagal mempersiapkan statement.']);
        }
    } else {
        echo json_encode(['message' => 'Input tidak valid!']);
    }
} else {
    echo json_encode(['message' => 'Request method harus POST!']);
}

// Tutup koneksi
$conn->close();
?>
