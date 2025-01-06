<?php
// Include database connection
$conn = new mysqli("localhost", "root", "", "appblangkis");

// Get the user_id from the query parameters
$user_id = $_GET['user_id'];

// Initialize response array
$response = array();

// Check if user_id is provided
if (!empty($user_id)) {
    // Query to get the total price (harga_akhir) for the specific user
    $query = "SELECT SUM(total_harga) AS harga_akhir 
              FROM orders 
              WHERE user_id = ?";

    if ($stmt = $conn->prepare($query)) {
        // Bind the user_id parameter to the query
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        
        // Store the result
        $stmt->store_result();
        
        // Bind the result to a variable
        $stmt->bind_result($harga_akhir);
        
        // Check if the query returned a result
        if ($stmt->fetch()) {
            // Prepare the response with the total price
            $response['harga_akhir'] = $harga_akhir;

            // Set the response code to 200 (success)
            http_response_code(200);
        } else {
            // If no orders are found for the given user_id
            $response['message'] = "No orders found for this user.";
            http_response_code(404);
        }

        // Close the statement
        $stmt->close();
    } else {
        // If the query couldn't be prepared
        $response['message'] = "Failed to prepare the query.";
        http_response_code(500);
    }
} else {
    // If user_id is not provided
    $response['message'] = "User ID is required.";
    http_response_code(400);
}

// Output the JSON response
echo json_encode($response);

// Close the database connection
$conn->close();
?>
