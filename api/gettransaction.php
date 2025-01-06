<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_nota";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT totalBayar, linkBukti FROM nota WHERE id = 1";  // Sesuaikan query dengan kebutuhan Anda
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Ambil data
    $row = $result->fetch_assoc();
    echo json_encode($row);  // Mengirim data dalam format JSON
} else {
    echo json_encode(['totalBayar' => 0, 'linkBukti' => '']);  // Jika tidak ada data
}

$conn->close();
?>
