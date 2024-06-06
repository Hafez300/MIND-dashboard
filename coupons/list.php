<?php

// Set the content type header to JSON
header('Content-Type: application/json');

// Include the configuration file
include('../config.php');

// Check if the required parameters are set in the POST request
if (isset($_POST['user_id'])) {

    // Sanitize and escape the input parameters
    $auth_key = mysqli_real_escape_string($conn, $_POST['auth_key']);
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);

    // Query to check the validity of the auth_key
    $sql = "SELECT auth_key FROM auth WHERE auth_key='$auth_key'";
    $key = mysqli_query($conn, $sql);

    // Check if the auth_key is valid
    if (mysqli_num_rows($key) >= 1) {

        // Construct the SQL query to fetch all coupon codes
        $query = mysqli_query($conn, "SELECT * FROM coupon_codes");

        // Check if any coupon codes are found
        if (mysqli_num_rows($query) >= 1) {
            $coupon_list = array(); // Initialize an array to store coupon details
            
            // Iterate through each row of the query result
            while ($row = mysqli_fetch_assoc($query)) {
                // Add each coupon detail to the coupon list array
                $coupon_list[] = $row;
            }

            // Return success response with the list of coupons
            echo json_encode(array('status' => true, 'msg' => 'success', "coupon_list" => $coupon_list));
        } else {
            // Return error response if no coupons are found
            echo json_encode(array('status' => false, 'msg' => 'No coupons found', "coupon_list" => array()));
        }
    } else {
        // Return authentication error if auth_key is invalid
        echo json_encode(array('status' => false, 'msg' => 'Wrong key', "coupon_list" => null));
    }
} else {
    // Return error if user_id is missing in the request
    echo json_encode(array('status' => false, 'msg' => 'user_id is required', "coupon_list" => null));
}

?>
