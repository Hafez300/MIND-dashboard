<?php

// Set the content type header to JSON
header('Content-Type: application/json');

// Include the configuration file
include('../config.php');

// Check if the required parameters are set in the POST request
if (isset($_POST['auth_key'])) {
    // Sanitize and escape the input parameter
    $auth_key = mysqli_real_escape_string($conn, $_POST['auth_key']);

    // Validate if any required parameters are empty
    if (empty($auth_key)) {
        echo json_encode(array('status' => false, 'msg' => 'Empty parameters'));
        exit;
    }

    // Query to check the validity of the auth_key and ensure the user is an administrator
    $sql = "SELECT * FROM auth WHERE auth_key='$auth_key'";
    $key = mysqli_query($conn, $sql);

    // Check if the auth_key is valid and the user is an administrator
    if (mysqli_num_rows($key) >= 1) {
        // Construct the SQL query to retrieve all notifications
        $notification_sql = "SELECT * FROM notifications";

        // Execute the SQL query
        $result = mysqli_query($conn, $notification_sql);

        // Check if notifications are found
        if (mysqli_num_rows($result) > 0) {
            $notifications = array();
            // Fetch each row from the result set
            while ($row = mysqli_fetch_assoc($result)) {
                // Add each notification to the array
                $notifications[] = $row;
            }
            // Return the notifications as JSON response
            echo json_encode(array('status' => true, 'notifications' => $notifications));
            mysqli_close($conn);
        } else {
            // Return message if no notifications are found
            echo json_encode(array('status' => false, 'msg' => 'No notifications found'));
            mysqli_close($conn);
        }
    } else {
        // Return authentication error if auth_key is invalid or user is not an administrator
        echo json_encode(array('status' => false, 'msg' => 'Invalid credentials or insufficient privileges'));
    }
} else {
    // Return error if any required parameters are missing in the request
    echo json_encode(array('status' => false, 'msg' => 'Required parameters missing'));
}
?>
