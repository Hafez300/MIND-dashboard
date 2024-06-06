<?php 

header('Content-Type: application/json');

include('../config.php');

// Check if required POST parameters are set
if (isset($_POST['user_id']) && isset($_POST['auth_key']) && isset($_POST['product_id'])) {

    // Sanitize input to prevent SQL injection
    $auth_key = mysqli_real_escape_string($conn, $_POST['auth_key']);
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
   


    
    

    // Validate authentication key
    $sql = "SELECT auth_key FROM auth WHERE auth_key='$auth_key'";
    $key = mysqli_query($conn, $sql);

    if (mysqli_num_rows($key) >= 1) {
        
        // Retrieve file names for images
    $image_en_name = $_FILES['image_en']['name'];
        // Generate unique filenames for the images
        $filename_en = explode(".", $image_en_name);
        $filename_en[0] = time() . "_en." . end($filename_en);
        $image_en = $baseurl . $filename_en[0];
        $target_en = "images/" . basename($image_en);



     // Retrieve file names for images
    $image_ar_name = $_FILES['image_ar']['name'];
        // Generate unique filenames for the images
        $filename_ar = explode(".", $image_ar_name);
        $filename_ar[0] = time() . "_ar." . end($filename_ar);
        $image_ar = $baseurl . $filename_ar[0];
        $target_ar = "images/" . basename($image_ar);
        move_uploaded_file($_FILES['image_ar']['tmp_name'], $target_ar);
        
        // Move uploaded files to the target directory
        if (move_uploaded_file($_FILES['image_en']['tmp_name'], $target_en)) {

            // Insert new record into the slider table
            $slider_sql = "INSERT INTO slider (
                `product_id`,
                `image_en`,
             `image_ar`
            ) VALUES (
                '$product_id',
                '$filename_en[0]',
            '$filename_ar[0]'
            )";

            // Execute the insert query
            $register = mysqli_query($conn, $slider_sql) or $error = mysqli_error($conn);

            // Get the ID of the newly inserted record
            $last_id = mysqli_insert_id($conn);

            // Check if the record was successfully inserted
            if ($register) {
                // Return success response
                echo json_encode(array('status' => true, 'msg' => 'success', "slider_id" => $last_id));
                mysqli_close($conn);
            } else {
                // Return error response if insertion failed
                echo json_encode(array('status' => true, 'msg' => 'Nothing changed', "slider_id" => $error));
                mysqli_close($conn);
            }

        } else {
            // Return error response if image upload failed
            echo json_encode(array('status' => false, 'msg' => 'Image upload failed', "slider_id" => null));
            mysqli_close($conn);
        }

    } else {
        // Return error response if authentication key is invalid
        echo json_encode(array('status' => false, 'msg' => 'Wrong key', "slider_id" => null));
    }

} else {
    // Return error response if required parameters are missing
    echo json_encode(array('status' => false, 'msg' => 'user_id and product_id are required', "slider_id" => null));
}

?>
