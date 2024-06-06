<?php

// Set the content type header to JSON
header('Content-Type: application/json');

// Include the configuration file
include('../config.php');

// Check if the required parameters are set in the POST request
if (isset($_POST['auth_key']) && isset($_POST['user_id'])) {
    // Sanitize and escape the input parameter
    $auth_key = mysqli_real_escape_string($conn, $_POST['auth_key']);

    // Validate if any required parameters are empty
    if (empty($auth_key)) {
        echo json_encode(array('status' => false, 'msg' => 'Empty parameters'));
        exit;
    }

    // Query to check the validity of the auth_key
    $sql = "SELECT * FROM auth WHERE auth_key='$auth_key'";
    $key = mysqli_query($conn, $sql);

    // Check if the auth_key is valid
    if (mysqli_num_rows($key) >= 1) {
        // Construct the SQL query to retrieve order status data
        $status_sql = "SELECT * FROM orders_status";

        // Execute the SQL query
        $result = mysqli_query($conn, $status_sql);

        // Check if order status data is found
        if (mysqli_num_rows($result) > 0) {
            $statuses = array();
            // Fetch each row from the result set
            while ($row = mysqli_fetch_assoc($result)) {
                // Add each status to the array
                $statuses[] = $row;
            }
            // Return the statuses as JSON response
            echo json_encode(array('status' => true, 'statuses' => $statuses));
            mysqli_close($conn);
        } else {
            // Return message if no order status data is found
            echo json_encode(array('status' => true, 'msg', 'statuses' => []));
            mysqli_close($conn);
        }
    } else {
        // Return authentication error if auth_key is invalid
        echo json_encode(array('status' => false, 'msg' => 'Invalid credentials'));
    }
} else {
    // Return error if any required parameters are missing in the request
    echo json_encode(array('status' => false, 'msg' => 'Required parameters missing'));
}
?>
