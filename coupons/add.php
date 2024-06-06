<?php

// Set the content type header to JSON
header('Content-Type: application/json');

// Include the configuration file
include('../config.php');

// Check if the required parameters are set in the POST request
if (isset($_POST['user_id'], $_POST['auth_key'], $_POST['coupon'], $_POST['discount'], $_POST['is_active'])) {

    // Sanitize and escape the input parameters
    $auth_key = mysqli_real_escape_string($conn, $_POST['auth_key']);
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $coupon = mysqli_real_escape_string($conn, $_POST['coupon']);
    $discount = mysqli_real_escape_string($conn, $_POST['discount']);
    $is_active = mysqli_real_escape_string($conn, $_POST['is_active']);


    // Validate if any required parameters are empty
    if (empty($auth_key) || empty($user_id) || empty($coupon) || empty($discount) ||($is_active)==null) {
        echo json_encode(array('status' => false, 'msg' => 'Empty parameters', "coupon_id" => null));
        exit;
    }

    // Query to check the validity of the auth_key
    $sql = "SELECT auth_key FROM auth WHERE auth_key='$auth_key'";
    $key = mysqli_query($conn, $sql);

    // Check if the auth_key is valid
    if (mysqli_num_rows($key) >= 1) {

        // Construct the SQL query to insert coupon details
        $coupon_sql = "INSERT INTO coupon_codes (`coupon`, `discount`, `is_active`) 
                       VALUES ('$coupon', '$discount', '$is_active')";

        // Execute the SQL query
        $register = mysqli_query($conn, $coupon_sql) or $error = mysqli_error($conn);

        // Get the ID of the last inserted record
        $last_id = mysqli_insert_id($conn);

        // Check if the insertion was successful
        if ($register) {
            // Return success response with the ID of the newly inserted coupon
            echo json_encode(array('status' => true, 'msg' => 'success', "coupon_id" => $last_id));
            mysqli_close($conn);
        } else {
            // Return failure response with the error message
            echo json_encode(array('status' => false, 'msg' => 'Failed to add coupon', "coupon_id" => $error));
            mysqli_close($conn);
        }
    } else {
        // Return authentication error if auth_key is invalid
        echo json_encode(array('status' => false, 'msg' => 'Wrong key', "coupon_id" => null));
    }
} else {
    // Return error if any required parameters are missing in the request
    echo json_encode(array('status' => false, 'msg' => 'Required parameters missing', "coupon_id" => null));
}

?>
