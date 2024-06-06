<?php

// Set the content type header to JSON
header('Content-Type: application/json');

// Include the configuration file
include('../config.php');

// Check if the required parameters are set in the POST request
if (isset($_POST['user_id']) && isset($_POST['auth_key'])){

    // Sanitize and escape the input parameters
    $auth_key = mysqli_real_escape_string($conn, $_POST['auth_key']);
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    

    // Query to check the validity of the auth_key
    $sql = "SELECT auth_key FROM auth WHERE auth_key='$auth_key'";
    $key = mysqli_query($conn, $sql);

    // Check if the auth_key is valid
    if (mysqli_num_rows($key) >= 1) {

        // Construct the SQL query to fetch reviews
        $reviews_sql = "SELECT * FROM reviews";

        // Execute the SQL query
        $result = mysqli_query($conn, $reviews_sql);

        // Check if there are reviews
        if (mysqli_num_rows($result) > 0) {
            // Array to store reviews
            $reviews = array();

            // Fetch reviews and add them to the array
            while ($row = mysqli_fetch_assoc($result)) {
                $reviews[] = $row;
            }

            // Return success response with the reviews
            echo json_encode(array('status' => true, 'msg' => 'success', "reviews" => $reviews));
            mysqli_close($conn);
        } else {
            // Return failure response if no reviews found
            echo json_encode(array('status' => false, 'msg' => 'No reviews found', "reviews" => array()));
            mysqli_close($conn);
        }
    } else {
        // Return authentication error if auth_key is invalid
        echo json_encode(array('status' => false, 'msg' => 'Wrong key', "reviews" => null));
    }
} else {
    // Return error if user_id is missing in the request
    echo json_encode(array('status' => false, 'msg' => 'user_id and product_id are required', "reviews" => null));
}

?>
