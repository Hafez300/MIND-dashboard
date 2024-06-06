<?php

// Set the content type header to JSON
header('Content-Type: application/json');

// Include the configuration file
include('../config.php');

// Check if the required parameters are set in the POST request
if (isset($_POST['auth_key'], $_POST['title_en'], $_POST['title_ar'], $_POST['body_en'], $_POST['body_ar'])) {
    // Sanitize and escape the input parameters
    $auth_key = mysqli_real_escape_string($conn, $_POST['auth_key']);
    $title_en = mysqli_real_escape_string($conn, $_POST['title_en']);
    $title_ar = mysqli_real_escape_string($conn, $_POST['title_ar']);
    $body_en = mysqli_real_escape_string($conn, $_POST['body_en']);
    $body_ar = mysqli_real_escape_string($conn, $_POST['body_ar']);
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);


    // Validate if any required parameters are empty
    if (empty($auth_key) || empty($title_en) || empty($title_ar) || empty($body_en) || empty($body_ar)) {
        echo json_encode(array('status' => false, 'msg' => 'Empty parameters'));
        exit;
    }
    
    // Query to check the validity of the auth_key and ensure the user is an administrator
    $sql = "SELECT * FROM auth WHERE auth_key='$auth_key'";
    $key = mysqli_query($conn, $sql);

    // Check if the auth_key is valid and the user is an administrator
    if (mysqli_num_rows($key) >= 1) {
        // Construct the SQL query to insert notification details for all users
        $notification_sql = "INSERT INTO notifications (`title_en`, `title_ar`, `body_en`, `body_ar`, `user_id`) 
                                    VALUES ('$title_en', '$title_ar', '$body_en', '$body_ar', 0)";
                            

        // Execute the SQL query
        $result = mysqli_query($conn, $notification_sql);

        // Check if the insertion was successful
        if ($result) {
            // Return success response
            echo json_encode(array('status' => true, 'msg' => 'Notification created successfully for all users'));
            mysqli_close($conn);
        } else {
            // Return failure response with the error message
            echo json_encode(array('status' => false, 'msg' => 'Failed to create notification for all users', 'error' => mysqli_error($conn)));
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
