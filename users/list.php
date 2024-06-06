<?php

// Set the content type header to JSON
header('Content-Type: application/json');

// Include the configuration file
include('../config.php');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['auth_key']) && isset($_POST['user_id'])) {

    // Ensure limit and count are set and are integers
    $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 10; // Default to 10 if not set
    $count = isset($_POST['count']) ? (int)$_POST['count'] : 0;  // Default to 0 if not set

    // Escape and sanitize inputs
    $auth_key = mysqli_real_escape_string($conn, $_POST['auth_key']);
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);

    // Construct the SQL query to fetch users with limit and offset
    $query = "SELECT id, email, name, phone, image, active FROM users LIMIT $limit OFFSET $count";
    $result = mysqli_query($conn, $query);

    // Check if any users are found
    if (mysqli_num_rows($result) > 0) {
        $user_list = array(); // Initialize an array to store user details

        // Iterate through each row of the query result
        while ($row = mysqli_fetch_assoc($result)) {
            // Add each user detail to the user list array
            $user_list[] = $row;
        }

        // Return success response with the list of users
        echo json_encode(array('status' => true, 'msg' => 'success', 'user_list' => $user_list));
    } else {
        // Return error response if no users are found
        echo json_encode(array('status' => false, 'msg' => 'No users found', 'user_list' => array()));
    }
} else {
    // Return error if the request method is not POST or required fields are missing
    http_response_code(405); // Method Not Allowed
    echo json_encode(array('status' => false, 'msg' => 'Method Not Allowed', 'user_list' => null));
}

?>
