<?php
header('Content-Type: application/json');

include('../config.php');

if (isset($_POST['auth_key']) && isset($_POST['user_id']) && isset($_POST['main_image']) && isset($_POST['product_id'])) {
    $auth_key = mysqli_real_escape_string($conn, $_POST['auth_key']);
    $image = mysqli_real_escape_string($conn, $_POST['main_image']);
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $thumb = $image;

    $sql = "SELECT auth_key FROM auth WHERE auth_key='$auth_key'";
    $key = mysqli_query($conn, $sql);

    if (mysqli_num_rows($key) >= 1) {
        $update_sql = "UPDATE `products` SET `main_image`= '$image', `thumb_image`= '$thumb' WHERE `id`='$product_id'";
        $update = mysqli_query($conn, $update_sql);

        if ($update) {
            echo json_encode(array('status' => true, 'msg' => 'Image and thumb updated successfully'));
        } else {
            echo json_encode(array('status' => true, 'msg' => 'Failed to update image and thumb'));
        }
    } else {
        echo json_encode(array('status' => false, 'msg' => 'Wrong key'));
    }
} else {
    echo json_encode(array('status' => false, 'msg' => 'Image name, authentication key, and product ID are required'));
}
mysqli_close($conn);
?>
