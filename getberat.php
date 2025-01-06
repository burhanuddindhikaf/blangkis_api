<?php
// Set headers agar respons dikembalikan dalam format JSON
header('Content-Type: application/json');

// Koneksi ke database
$servername = "localhost"; // Ganti dengan server database Anda
$username = "root"; // Ganti dengan username database Anda
$password = ""; // Ganti dengan password database Anda
$dbname = "appblangkis"; // Ganti dengan nama database Anda

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk menghitung total berat dari seluruh pesanan
$sql = "
    SELECT 
        SUM(orders.quantity * menu.berat) AS total_berat
    FROM 
        orders
    JOIN 
        menu ON orders.item_id = menu.id
";

$result = $conn->query($sql);

if (!$result) {
    die("Query gagal: " . $conn->error); // Tampilkan kesalahan query
}

$response = [
    'total_berat' => 0 // Default nilai total berat adalah 0
];

// Memeriksa apakah ada hasil
if ($row = $result->fetch_assoc()) {
    $response['total_berat'] = $row['total_berat'] ?? 0;
}

// Mengembalikan data dalam format JSON
echo json_encode($response);

// Menutup koneksi
$conn->close();
?>
