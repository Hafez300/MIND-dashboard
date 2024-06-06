<?php
header('Content-Type: application/json');

// Include the database configuration file
include('../config.php');

// Check if the required POST parameters are set
if (isset($_POST['auth_key']) && isset($_POST['user_id']) && isset($_POST['keyword'])) {
    $auth_key = mysqli_real_escape_string($conn, $_POST['auth_key']);
    $keyword = mysqli_real_escape_string($conn, $_POST['keyword']);

    // Check authentication
    $sql_auth = "SELECT auth_key FROM auth WHERE auth_key='$auth_key'";
    $result_auth = mysqli_query($conn, $sql_auth);

    if (mysqli_num_rows($result_auth) >= 1) {
        // Sanitize the input
        $keyword = htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8');

        // SQL query to search users by email
        $search_sql = "
        SELECT id, name, email, phone, active 
        FROM users
        WHERE id = '$keyword' OR email LIKE '%$keyword%'
        ";

        $result_users = mysqli_query($conn, $search_sql);

        $users = [];
        while ($row = mysqli_fetch_assoc($result_users)) {
            $users[] = $row;
        }

        echo json_encode(array('status' => true, 'users' => $users));
    } else {
        echo json_encode(array('status' => true, 'msg' => 'Wrong key', 'users' => []));
    }
} else {
    echo json_encode(array('status' => false, 'msg' => 'Authentication key and keyword are required'));
}

// Close the database connection
mysqli_close($conn);
?>
