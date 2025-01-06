<?php
// Establish the database connection
$conn = new mysqli("localhost", "root", "", "appblangkis");

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the POST data
$data = json_decode(file_get_contents('php://input'), true);

// Get values from the POST data
$item_id = $data['item_id'];
$user_id = $data['user_id'];
$harga = $data['harga'];  // Assuming the price is passed along with the request

// Define the quantity value (use 1 if not provided)
$quantity = isset($data['quantity']) ? $data['quantity'] : 1;

// Check if the order already exists in the database
$query_check = "SELECT quantity FROM orders WHERE item_id = ? AND user_id = ?";
$stmt_check = $conn->prepare($query_check);
$stmt_check->bind_param("ii", $item_id, $user_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

// If the order exists, update the quantity and total price
if ($result_check->num_rows > 0) {
    $existing_order = $result_check->fetch_assoc();
    $new_quantity = $existing_order['quantity'] + $quantity;
    $new_total_harga = $new_quantity * $harga; // Recalculate the total price

    // Update the order in the database
    $query_update = "UPDATE orders SET quantity = ?, total_harga = ? WHERE item_id = ? AND user_id = ?";
    $stmt_update = $conn->prepare($query_update);
    $stmt_update->bind_param("iiii", $new_quantity, $new_total_harga, $item_id, $user_id);

    if ($stmt_update->execute()) {
        echo json_encode(['message' => 'Order quantity and total price updated successfully']);
    } else {
        echo json_encode(['message' => 'Error updating order: ' . $stmt_update->error]);
    }

    $stmt_update->close();
} else {
    // If the order does not exist, insert a new record
    $new_total_harga = $quantity * $harga; // Calculate total price for the new order

    $query_insert = "INSERT INTO orders (item_id, quantity, user_id, total_harga) VALUES (?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($query_insert);
    $stmt_insert->bind_param("iiii", $item_id, $quantity, $user_id, $new_total_harga);

    if ($stmt_insert->execute()) {
        echo json_encode(['message' => 'Order added successfully']);
    } else {
        echo json_encode(['message' => 'Error adding order: ' . $stmt_insert->error]);
    }

    $stmt_insert->close();
}

// Close the connection
$conn->close();
?>
