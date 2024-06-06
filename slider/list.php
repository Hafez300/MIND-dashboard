<?php

// Set the response content type to JSON
header('Content-Type: application/json');

// Include the database configuration file
include('../config.php');

// Check if the required POST parameters are set
if (isset($_POST['user_id'])) {

    // Sanitize input to prevent SQL injection
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $auth_key = mysqli_real_escape_string($conn, $_POST['auth_key']);

    // Validate the authentication key
    $sql = "SELECT auth_key FROM auth WHERE auth_key='$auth_key'";
    $key = mysqli_query($conn, $sql);

    // Check if the authentication key is valid
    if (mysqli_num_rows($key) >= 1) {

        // Query to select all slider records
        $query = mysqli_query($conn, "SELECT * FROM slider");

        // Check if there are slider records found
        if (mysqli_num_rows($query) >= 1) {
            $temp_array = array();
            // Fetch each slider record
            while ($row = mysqli_fetch_assoc($query)) {
                // Adjust image URLs based on the directory structure
                isset($row["image_en"]) ? $row["image_en"] = $baseurl . "slider/images/" . $row["image_en"] : null;
                isset($row["image_ar"]) ? $row["image_ar"] = $baseurl . "slider/images/" . $row["image_ar"] : null;
                // Add the modified record to the temporary array
                $temp_array[] = $row;
            }

            // Return the list of slider records as JSON
            echo json_encode(array('status' => true, 'msg' => 'success', "list" => $temp_array));
        } else {
            // Return a message indicating no slider records were found
            echo json_encode(array('status' => true, 'msg' => 'No Data', "list" => []));
        }
    } else {
        // Return an error message for an invalid authentication key
        echo json_encode(array('status' => false, 'msg' => 'Wrong key', "list" => []));
    }
} else {
    // Return an error message for missing parameters
    echo json_encode(array('status' => false, 'msg' => 'Missing Parameters', "list" => []));
}

?>
