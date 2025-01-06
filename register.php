<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

$conn = new mysqli("localhost","root","","appblangkis"); 


// Mengecek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Menerima data dari aplikasi Flutter
$data = json_decode(file_get_contents("php://input"));

$username = $data->username;
$password = $data->password;

// Memeriksa apakah username sudah terpakai
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Username sudah terpakai
    echo json_encode(["status" => "error", "message" => "Username telah terpakai"]);
    exit();
}

// Menambahkan pengguna baru
$sql = "INSERT INTO users (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);
if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Registrasi berhasil"]);
} else {
    echo json_encode(["status" => "error", "message" => "Terjadi kesalahan, coba lagi"]);
}

// Menutup koneksi
$stmt->close();
$conn->close();
?>