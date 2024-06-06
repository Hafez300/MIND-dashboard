<?php

// Set the content type header to JSON
header('Content-Type: application/json');

// Include the configuration file
include('../config.php');

// Check if the required parameters are set in the POST request
if (isset($_POST['user_id'], $_POST['id'], $_POST['active'])) {

    // Sanitize and escape the input parameters
    $auth_key = mysqli_real_escape_string($conn, $_POST['auth_key']);
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $active = mysqli_real_escape_string($conn, $_POST['active']);

    // Convert active to integer (0 or 1)
    $active = (int) $active;

    // Validate active value (should be 0 or 1)
    if ($active !== 0 && $active !== 1) {
        echo json_encode(array('status' => false, 'msg' => 'active value must be 0 or 1'));
        exit();
    }

    // Query to check the validity of the auth_key
    $sql = "SELECT auth_key FROM auth WHERE auth_key='$auth_key'";
    $key = mysqli_query($conn, $sql);

    // Check if the auth_key is valid
    if (mysqli_num_rows($key) >= 1) {

        // Construct the SQL query to update review active
        $update_sql = "UPDATE reviews SET active = '$active' WHERE id = '$id'";

        // Execute the SQL query
        $update = mysqli_query($conn, $update_sql);

        // Check if the update was successful
        if ($update) {
            // Return success response
            echo json_encode(array('status' => true, 'msg' => 'Review active updated successfully'));
            mysqli_close($conn);
        } else {
            // Return failure response
            echo json_encode(array('status' => false, 'msg' => 'Failed to update review active'));
            mysqli_close($conn);
        }
    } else {
        // Return authentication error if auth_key is invalid
        echo json_encode(array('status' => false, 'msg' => 'Wrong key'));
    }
} else {
    // Return error if required parameters are missing in the request
    echo json_encode(array('status' => false, 'msg' => 'user_id, id, and active are required'));
}

?>
