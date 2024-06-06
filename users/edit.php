<?php

// Set the content type header to JSON
header('Content-Type: application/json');

// Include the configuration file
include('../config.php');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Check if the required parameters are set in the POST request
    if (isset($_POST['user_id'], $_POST['email'], $_POST['auth_key'], $_POST['active'])) {

        // Sanitize and escape the input parameters
        $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $auth_key = mysqli_real_escape_string($conn, $_POST['auth_key']);
        $active = mysqli_real_escape_string($conn, $_POST['active']);
        
        // Store the email before appending to match when banned
        $temp_mail = $email;

        // Append slash sign to email and auth_key if user is inactive (banned)
        if ($active == 0) {
            $email .= '/';
            $auth_key .= '/';
            $active = 0;
        }

        // Query to update user details and active status
        $sql = "UPDATE users SET auth_key='$auth_key', active='$active', email='$email' WHERE email='$temp_mail'";
        $result = mysqli_query($conn, $sql);

        // Check if the query was successful
        if ($result) {
            // Return success response
            echo json_encode(array('status' => true, 'msg' => 'User details updated successfully'));
        } else {
            // Return error response if the query fails
            echo json_encode(array('status' => false, 'msg' => 'Failed to update user details'));
        }
    } else {
        // Return error if any required parameters are missing
        echo json_encode(array('status' => false, 'msg' => 'user_id, email, auth_key, and active are required'));
    }
} else {
    // Return error if the request method is not POST
    http_response_code(405); // Method Not Allowed
    echo json_encode(array('status' => false, 'msg' => 'Method Not Allowed'));
}

?>
