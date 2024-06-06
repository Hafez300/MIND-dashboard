<?php
header('Content-Type: application/json');

include('../config.php');

if (isset($_POST['auth_key']) && isset($_POST['user_id']) && isset($_POST['image_id'])) {
    $auth_key = mysqli_real_escape_string($conn, $_POST['auth_key']);
    $image_id = mysqli_real_escape_string($conn, $_POST['image_id']);

    $sql = "SELECT auth_key FROM auth WHERE auth_key='$auth_key'";
    $key = mysqli_query($conn, $sql);

    if (mysqli_num_rows($key) >= 1) {
        $delete_sql = "DELETE FROM `images` WHERE `id`='$image_id'";
        $delete = mysqli_query($conn, $delete_sql);

        if ($delete) {
            echo json_encode(array('status' => true, 'msg' => 'Image deleted successfully'));
        } else {
            echo json_encode(array('status' => true, 'msg' => 'Failed to delete image'));
        }
    } else {
        echo json_encode(array('status' => false, 'msg' => 'Wrong key'));
    }
} else {
    echo json_encode(array('status' => false, 'msg' => 'Image ID and authentication key are required'));
}
mysqli_close($conn);
?>
