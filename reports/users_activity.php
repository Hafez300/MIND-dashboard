<?php

// Set the content type header to JSON
header('Content-Type: application/json');

// Include the configuration file
include('../config.php');

// Check if the required parameters are set in the POST request
if (isset($_POST['auth_key']) && isset($_POST['user_id'])) {
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
        // Construct the SQL query to retrieve return items with related product_id
        $user_report_sql = "
            SELECT 
                CASE WHEN active = 1 THEN 'active' ELSE 'inactive' END AS status,
                COUNT(*) as user_count 
            FROM 
                users 
            GROUP BY 
                active
        ";

        // Execute the SQL query
        $result = mysqli_query($conn, $user_report_sql);

        // Initialize counters for active and inactive users
        $user_report = array('active_users' => 0, 'inactive_users' => 0);

        // Check if users are found
        if (mysqli_num_rows($result) > 0) {
            // Fetch each row from the result set
            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['status'] == 'active') {
                    $user_report['active_users'] = $row['user_count'];
                } else {
                    $user_report['inactive_users'] = $row['user_count'];
                }
            }
            // Return the user report as JSON response
            echo json_encode(array('status' => true, 'user_report' => $user_report));
        } else {
            // Return message if no users are found
            echo json_encode(array('status' => true, 'msg', 'user_report' => []));
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