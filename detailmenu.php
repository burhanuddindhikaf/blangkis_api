<?php
$conn = new mysqli("localhost","root","","appblangkis"); 

header("Content-Type: application/json");

$id = $_GET['id'];

$query = "SELECT * FROM menu WHERE id = '$id'";
$sql = mysqli_query($conn, $query);

if ($sql && mysqli_num_rows($sql) > 0) {
    $row = mysqli_fetch_assoc($sql);
    echo json_encode([
        "status" => "success",
        "data" => [
            "nama" => $row['nama'],
            "deskripsi" => $row['deskripsi'],
            "linkGambar" => $row['linkGambar'],
            "harga" => $row['harga'],
        ],
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Item not found",
    ]);
}
?>
