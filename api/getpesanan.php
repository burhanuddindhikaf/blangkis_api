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

// Query untuk mengambil data pesanan yang menggabungkan tabel `orders` dan `menu`
// Serta menghitung `totalberat`
$sql = "
    SELECT 
        orders.order_id,
        menu.nama AS item_nama,
        menu.linkGambar AS item_linkGambar,
        orders.quantity,
        orders.total_harga,
        (orders.quantity * menu.berat) AS totalberat
    FROM 
        orders
    JOIN 
        menu ON orders.item_id = menu.id
";

$result = $conn->query($sql);

if (!$result) {
    die("Query gagal: " . $conn->error); // Tampilkan kesalahan query
}

$pesananArray = [];

// Memeriksa apakah ada hasil
if ($result->num_rows > 0) {
    // Mengambil data untuk setiap baris
    while ($row = $result->fetch_assoc()) {
        $pesananArray[] = [
            'order_id' => $row['order_id'],
            'item_nama' => $row['item_nama'],
            'item_linkGambar' => $row['item_linkGambar'],
            'quantity' => $row['quantity'],
            'total_harga' => $row['total_harga'],
            'totalberat' => $row['totalberat']
        ];
    }

    // Mengembalikan data dalam format JSON
    echo json_encode($pesananArray);
} else {
    echo json_encode([]); // Jika tidak ada data, kirim array kosong
}

// Menutup koneksi
$conn->close();
?>
