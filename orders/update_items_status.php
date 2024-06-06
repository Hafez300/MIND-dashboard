<?php

// Set the content type header to JSON
header('Content-Type: application/json');

// Include the configuration file
include('../config.php');

// Check if the required parameters are set in the POST request
if (isset($_POST['auth_key']) && isset($_POST['user_id']) && isset($_POST['order_id']) && isset($_POST['status']) && isset($_POST['id'])) {
    // Sanitize and escape the input parameters
    $auth_key = mysqli_real_escape_string($conn, $_POST['auth_key']);
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $id = mysqli_real_escape_string($conn, $_POST['id']);


    // Validate if any required parameters are empty
    if (empty($auth_key) || empty($order_id) || empty($status) || empty($id)){
        echo json_encode(array('status' => false, 'msg' => 'Empty parameters'));
        exit;
    }

    // Query to check the validity of the auth_key
    $sql = "SELECT * FROM auth WHERE auth_key='$auth_key'";
    $key = mysqli_query($conn, $sql);

    // Check if the auth_key is valid
    if (mysqli_num_rows($key) >= 1) {
        // Construct the SQL query to update the status of the order
        $update_status_sql = "UPDATE order_items SET status = '$status' WHERE id = '$id'";

        // Execute the SQL query to update the status
        if (mysqli_query($conn, $update_status_sql)) {
            echo json_encode(array('status' => true, 'msg' => 'Order status updated successfully'));
        } else {
            echo json_encode(array('status' => false, 'msg' => 'Failed to update order status'));
        }
    } else {
        // Return authentication error if auth_key is invalid
        echo json_encode(array('status' => false, 'msg' => 'Invalid credentials'));
    }
} else {
    // Return error if any required parameters are missing in the request
    echo json_encode(array('status' => false, 'msg' => 'Required parameters missing'));
}

// Close the database connection
mysqli_close($conn);

?>
