<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost","root","","appblangkis"); 


// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query untuk mengambil data menu
$sql = "SELECT * FROM menu";
$result = $conn->query($sql);

$menu = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $menu[] = $row;
    }
} else {
    echo json_encode([]);
}

// Mengirimkan data dalam format JSON
echo json_encode($menu);

// Menutup koneksi
$conn->close();
?>
