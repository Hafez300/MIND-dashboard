<?php
header('Content-Type: application/json');

include('../config.php');

if (isset($_POST['auth_key']) && isset($_POST['user_id']) && isset($_POST['keyword'])) {
    $auth_key = mysqli_real_escape_string($conn, $_POST['auth_key']);
    $keyword = mysqli_real_escape_string($conn, $_POST['keyword']);

    // Check authentication
    $sql_auth = "SELECT auth_key FROM auth WHERE auth_key='$auth_key'";
    $result_auth = mysqli_query($conn, $sql_auth);

    if (mysqli_num_rows($result_auth) >= 1) {
        // Sanitize the input
        $keyword = htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8');

        // SQL query to search products
        $search_sql = "
            SELECT 
                `products`.`id`,
                `products`.`name_en`,
                `products`.`name_ar`,
                `products`.`short_desc_en`,
                `products`.`short_desc_ar`,
                `products`.`description_en`,
                `products`.`description_ar`,
                `products`.`quantity`,
                `products`.`category_id`,
                GROUP_CONCAT(DISTINCT categories.cat_name_en ORDER BY categories.id) AS category_name,
                `products`.`group_id`,
                GROUP_CONCAT(DISTINCT groups.group_name_en ORDER BY groups.id) AS group_name,
                `products`.`main_image`,
                `products`.`thumb_image`,
                `products`.`sku`,
                `products`.`list_price`,
                `products`.`sale_price`,
                `products`.`barcode`,
                `products`.`added_by`,
                `products`.`added_on`,
                `products`.`main_product`,
                `products`.`active`,
                (SELECT COUNT(*) FROM products) AS total_products
            FROM products
            INNER JOIN categories ON FIND_IN_SET(categories.id, products.category_id) > 0
            LEFT JOIN groups ON FIND_IN_SET(groups.id, products.group_id) > 0
            WHERE products.active = 1
            AND (products.name_en LIKE '%$keyword%' OR products.name_ar LIKE '%$keyword%')
            GROUP BY `products`.`id`
        ";

        $result_products = mysqli_query($conn, $search_sql);

        $products = [];
        while ($row = mysqli_fetch_assoc($result_products)) {
            isset($row["main_image"])?$row["main_image"] =  $baseurl."products/uploads/".$row["main_image"]:null;
            isset($row["thumb_image"])?$row["thumb_image"] =  $baseurl."products/uploads/thumbs/".$row["thumb_image"]:null;
            $temp_array[] = $row;
            $products[] = $row;
        }

        echo json_encode(array('status' => true, 'products' => $products));
    } else {
        echo json_encode(array('status' => true, 'msg' => 'Wrong key', 'products' => []));
    }
} else {
    echo json_encode(array('status' => false, 'msg' => 'Authentication key, user ID, and keyword are required'));
}

mysqli_close($conn);
?>
