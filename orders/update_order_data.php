<?php

// Set the content type header to JSON
header('Content-Type: application/json');

// Include the configuration file
include('../config.php');

// Check if the required parameters are set in the POST request
if (isset($_POST['auth_key']) && isset($_POST['user_id']) && isset($_POST['order_id']) && isset($_POST['order_status']) && isset($_POST['arrival_date'])) {
    // Sanitize and escape the input parameters
    $auth_key = mysqli_real_escape_string($conn, $_POST['auth_key']);
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
    $order_status = mysqli_real_escape_string($conn, $_POST['order_status']);
    $arrival_date = mysqli_real_escape_string($conn, $_POST['arrival_date']);

    // Validate if any required parameters are empty
    if (empty($auth_key) || empty($order_id) || empty($order_status)) {
        echo json_encode(array('status' => false, 'msg' => 'Empty parameters'));
        exit;
    }

    // Query to check the validity of the auth_key and ensure the user is an administrator
    $sql = "SELECT * FROM auth WHERE auth_key='$auth_key'";
    $key = mysqli_query($conn, $sql);

    // Check if the auth_key is valid and the user is an administrator
    if (mysqli_num_rows($key) >= 1) {
        
        // Construct the SQL query to update order status and arrival date
        $update_sql = "UPDATE orders SET order_status = '$order_status'";
        
        // Add the arrival_date update if provided
        if (!empty($arrival_date)) {
            $update_sql .= ", arrival_date = '$arrival_date'";
        }
        
        // Append the WHERE clause
        $update_sql .= " WHERE id = $order_id";

        // Execute the SQL query
        $result = mysqli_query($conn, $update_sql);

        // Check if the update was successful
        if ($result) {
            // Return success message
            echo json_encode(array('status' => true, 'msg' => 'Order status and arrival date updated successfully'));
            mysqli_close($conn);
        } else {
            // Return error message if the update fails
            echo json_encode(array('status' => false, 'msg' => 'Failed to update order status and arrival date'));
            mysqli_close($conn);
        }
    } else {
        // Return authentication error if auth_key is invalid or user is not an administrator
        echo json_encode(array('status' => false, 'msg' => 'Unauthorized access'));
    }
} else {
    // Return error if any required parameters are missing in the request
    echo json_encode(array('status' => false, 'msg' => 'Required parameters missing'));
}
?>