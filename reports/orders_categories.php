<?php

// Set the content type header to JSON
header('Content-Type: application/json');

// Include the configuration file
include('../config.php');

// Check if the required parameters are set in the POST request
if (isset($_POST['auth_key'])&& isset($_POST['user_id'])) {
    // Sanitize and escape the input parameters
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
        // Construct the SQL query to retrieve category and count of orders in each category
        $order_category_count_sql = "
            SELECT 
                c.cat_name_en AS category_name, COUNT(oi.id) AS order_count
            FROM 
                categories c
            LEFT JOIN 
                products p ON c.id = p.category_id
            LEFT JOIN 
                order_items oi ON p.id = oi.product_id
            LEFT JOIN 
                orders o ON oi.order_id = o.id
            GROUP BY 
                c.id
        ";

        // Execute the SQL query
        $result = mysqli_query($conn, $order_category_count_sql);

        // Initialize an array to store order category count
        $order_category_count = array();

        // Check if order category count data is found
        if (mysqli_num_rows($result) > 0) {
            // Fetch each row from the result set
            while ($row = mysqli_fetch_assoc($result)) {
                // Add each category and order count to the array
                $order_category_count[] = $row;
            }
            // Return the order category count as JSON response
            echo json_encode(array('status' => true, 'order_category_count' => $order_category_count));
        } else {
            // Return message if no order category count data is found
            echo json_encode(array('status' => true, 'msg', 'order_category_count' => []));
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
