<?php

// Set the content type header to JSON
header('Content-Type: application/json');

// Include the configuration file
include('../config.php');

// Check if the required parameters are set in the POST request
if (isset($_POST['auth_key']) && isset($_POST['user_id'])) {
    // Sanitize and escape the input parameters
    $auth_key = mysqli_real_escape_string($conn, $_POST['auth_key']);
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']); // Retrieve user_id

    // Validate if any required parameters are empty
    if (empty($auth_key) || empty($user_id)) { // Check both auth_key and user_id
        echo json_encode(array('status' => false, 'msg' => 'Empty parameters'));
        exit;
    }

    // Query to check the validity of the auth_key
    $sql = "SELECT * FROM auth WHERE auth_key='$auth_key'";
    $key = mysqli_query($conn, $sql);

    // Check if the auth_key is valid
    if (mysqli_num_rows($key) >= 1) {
        // Construct the SQL query to retrieve return items with related product_id
        $return_items_sql = "
            SELECT 
                ri.id, ri.order_item_id, ri.reason_id, ri.description, ri.user_id, ri.added_on, 
                oi.product_id, oi.order_id
            FROM 
                return_item ri
            INNER JOIN 
                order_items oi ON ri.order_item_id = oi.id
            WHERE 
                oi.status=5";

        // Execute the SQL query
        $result = mysqli_query($conn, $return_items_sql);

        // Check if return items are found
        if (mysqli_num_rows($result) > 0) {
            $return_items = array();

            // Fetch each row from the result set
            while ($row = mysqli_fetch_assoc($result)) {
                // Add each return item to the array
                $return_items[] = $row;
            }

            // Return the return items as JSON response
            echo json_encode(array('status' => true, 'return_items' => $return_items));
        } else {
            // Return message if no return items are found
            echo json_encode(array('status' => true, 'return_items' => []));
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
