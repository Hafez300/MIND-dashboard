<?php

// Set the content type header to JSON
header('Content-Type: application/json');

// Include the configuration file
include('../config.php');

// Check if the required parameters are set in the POST request
if (isset($_POST['user_id'], $_POST['auth_key'], $_POST['coupon_id'], $_POST['is_active'], $_POST['discount'], $_POST['coupon'])) {

    // Sanitize and escape the input parameters
    $auth_key = mysqli_real_escape_string($conn, $_POST['auth_key']);
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $coupon_id = mysqli_real_escape_string($conn, $_POST['coupon_id']);
    $is_active = mysqli_real_escape_string($conn, $_POST['is_active']);
    $discount = mysqli_real_escape_string($conn, $_POST['discount']);
    $coupon = mysqli_real_escape_string($conn, $_POST['coupon']);





    // Query to check the validity of the auth_key
    $sql = "SELECT auth_key FROM auth WHERE auth_key='$auth_key'";
    $key = mysqli_query($conn, $sql);

    // Check if the auth_key is valid
    if (mysqli_num_rows($key) >= 1) {

        // Construct the SQL query to update coupon status to inactive
        $coupon_sql = "UPDATE coupon_codes SET `is_active` = '$is_active', `discount` = '$discount', `coupon` = '$coupon' WHERE `id` = '$coupon_id'";

        // Execute the SQL query
        $update = mysqli_query($conn, $coupon_sql) or $error = mysqli_error($conn);

        // Check if the update was successful
        if ($update) {
            // Return success response
            echo json_encode(array('status' => true, 'msg' => 'Coupon status updated successfully'));
            mysqli_close($conn);
        } else {
            // Return failure response with the error message
            echo json_encode(array('status' => false, 'msg' => 'Failed to update coupon status', 'error' => $error));
            mysqli_close($conn);
        }
    } else {
        // Return authentication error if auth_key is invalid
        echo json_encode(array('status' => false, 'msg' => 'Wrong key'));
    }
} else {
    // Return error if user_id or coupon_id is missing in the request
    echo json_encode(array('status' => false, 'msg' => 'user_id and coupon_id are required'));
}

?>
