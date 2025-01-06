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

// Query untuk mengambil user berdasarkan username dan password
$sql = "SELECT * FROM users WHERE username = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User ditemukan
    $user = $result->fetch_assoc();
    echo json_encode(["status" => "success", "id" => $user['id'], "username" => $user['username']]);
} else {
    // Username atau password salah
    echo json_encode(["status" => "error", "message" => "Username atau password salah"]);
}

// Menutup koneksi
$stmt->close();
$conn->close();
?>
