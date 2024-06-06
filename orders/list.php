<?php

// Set the content type header to JSON
header('Content-Type: application/json');

// Include the configuration file
include('../config.php');

// Check if the required parameters are set in the POST request
if (isset($_POST['auth_key']) && isset($_POST['user_id']) && isset($_POST['status_id'])) {
    // Sanitize and escape the input parameters
    $auth_key = mysqli_real_escape_string($conn, $_POST['auth_key']);
    $status_id = mysqli_real_escape_string($conn, $_POST['status_id']);

    // Validate if any required parameters are empty
    if (empty($auth_key)) {
        echo json_encode(array('status' => false, 'msg' => 'Empty parameters'));
        exit;
    }

    // Construct the WHERE clause based on the status_id
    $where = '';
    if ($status_id > 0) {
        $where = " WHERE orders.order_status = $status_id";
    }

    // Query to check the validity of the auth_key
    $sql = "SELECT * FROM auth WHERE auth_key='$auth_key'";
    $key = mysqli_query($conn, $sql);

    // Check if the auth_key is valid
    if (mysqli_num_rows($key) >= 1) {
        // Construct the SQL query to retrieve orders with their status
        $orders_sql = "SELECT orders.*, orders_status.status_en AS order_status_name, user_addresses.address
                        FROM orders
                        INNER JOIN orders_status ON orders.order_status = orders_status.id
                        INNER JOIN user_addresses ON orders.address_id = user_addresses.id";
        
        // Add the WHERE clause if needed
        $orders_sql .= $where;

        // Execute the SQL query
        $result = mysqli_query($conn, $orders_sql);

        // Check if orders are found
        if (mysqli_num_rows($result) > 0) {
            $orders = array();
            // Fetch each row from the result set
            while ($row = mysqli_fetch_assoc($result)) {
                // Add each order to the array
                $orders[] = $row;
            }
            // Return the orders as JSON response
            echo json_encode(array('status' => true, 'orders' => $orders));
        } else {
            // Return message if no orders are found
            echo json_encode(array('status' => true, 'msg', 'orders' => []));
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
