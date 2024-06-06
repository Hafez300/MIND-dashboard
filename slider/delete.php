<?php 

header('Content-Type: application/json');

include('../config.php');

// Check if required POST parameters are set
if (isset($_POST['user_id']) && isset($_POST['auth_key']) && isset($_POST['slider_id'])) {

    // Sanitize input to prevent SQL injection
    $auth_key = mysqli_real_escape_string($conn, $_POST['auth_key']);
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $slider_id = mysqli_real_escape_string($conn, $_POST['slider_id']);

    // Validate authentication key
    $sql = "SELECT auth_key FROM auth WHERE auth_key='$auth_key'";
    $key = mysqli_query($conn, $sql);

    if (mysqli_num_rows($key) >= 1) {

        // Check if the slider record exists
        $check_sql = "SELECT * FROM slider WHERE id='$slider_id'";
        $check_result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_result) > 0) {
            // Fetch the images associated with the slider record
            $slider_data = mysqli_fetch_assoc($check_result);
            $image_en = $slider_data['image_en'];
            $image_ar = $slider_data['image_ar'];

            // Delete the slider record
            $delete_sql = "DELETE FROM slider WHERE id='$slider_id'";
            $delete = mysqli_query($conn, $delete_sql) or $error = mysqli_error($conn);

            if ($delete) {
                // Delete the associated images from the server
                if (file_exists("images/$image_en")) {
                    unlink("images/$image_en");
                }
                if (file_exists("images/$image_ar")) {
                    unlink("images/$image_ar");
                }

                // Return success response
                echo json_encode(array('status' => true, 'msg' => 'Slider deleted successfully'));
                mysqli_close($conn);
            } else {
                // Return error response if deletion failed
                echo json_encode(array('status' => true, 'msg' => 'Deletion failed', 'error' => $error));
                mysqli_close($conn);
            }
        } else {
            // Return error response if slider record does not exist
            echo json_encode(array('status' => false, 'msg' => 'Slider record not found'));
        }

    } else {
        // Return error response if authentication key is invalid
        echo json_encode(array('status' => false, 'msg' => 'Wrong key'));
    }

} else {
    // Return error response if required parameters are missing
    echo json_encode(array('status' => false, 'msg' => 'user_id, auth_key, and slider_id are required'));
}

?>
