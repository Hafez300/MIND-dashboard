<?php
header('Content-Type: application/json');

include('../config.php');

if (isset($_POST['auth_key']) && isset($_POST['user_id']) && isset($_POST['question_en']) && isset($_POST['question_ar']) && isset($_POST['gender']) && isset($_POST['age'])) {
    $auth_key = mysqli_real_escape_string($conn, $_POST['auth_key']);
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $question_en = mysqli_real_escape_string($conn, $_POST['question_en']);
    $question_ar = mysqli_real_escape_string($conn, $_POST['question_ar']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);

    // Check if the auth_key is valid
    $sql = "SELECT auth_key FROM auth WHERE auth_key='$auth_key'";
    $key = mysqli_query($conn, $sql);

    if (mysqli_num_rows($key) >= 1) {
        // Initialize $w_image variable
        $w_image = '';

        // Handle file upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $image = $_FILES['image']['name'];
            $image_ext = pathinfo($image, PATHINFO_EXTENSION);
            $image_name = time() . '.' . $image_ext;
            $target = "images/" . $image_name;

            // Attempt to move the uploaded file
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $w_image = $image_name;
            } else {
                echo json_encode(array('status' => false, 'msg' => 'Failed to upload image', 'q_id' => null));
                mysqli_close($conn);
                exit();
            }
        } else {
            // Handle file upload error or no file uploaded
            if (isset($_FILES['image']['error']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                echo json_encode(array('status' => false, 'msg' => 'File upload error', 'q_id' => null));
                mysqli_close($conn);
                exit();
            }
        }

        // Insert data into the database
        $rating_sql = "INSERT INTO wellness_quiz (
            `question_en`, `question_ar`, `gender`, `age`, `image`
        ) VALUES (
            '$question_en', '$question_ar', '$gender', '$age', '$w_image'
        )";

        $register = mysqli_query($conn, $rating_sql) or $error = mysqli_error($conn);

        $last_id = mysqli_insert_id($conn);

        if ($register) {
            echo json_encode(array('status' => true, 'msg' => 'success', 'q_id' => $last_id));
        } else {
            echo json_encode(array('status' => false, 'msg' => 'Database error', 'q_id' => $error));
        }
    } else {
        echo json_encode(array('status' => false, 'msg' => 'Wrong key', 'q_id' => null));
    }
} else {
    echo json_encode(array('status' => false, 'msg' => 'Authentication key, user ID, question (EN & AR), gender, and age are required', 'q_id' => null));
}

mysqli_close($conn);
?>
