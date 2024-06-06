<?php

// Set the content type header to JSON
header('Content-Type: application/json');

// Include the configuration file
include('../config.php');

// Check if the required parameters are set in the POST request
if (isset($_POST['auth_key']) && isset($_POST['user_id']) && isset($_POST['order_id'])) {
    // Sanitize and escape the input parameter
    $auth_key = mysqli_real_escape_string($conn, $_POST['auth_key']);
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);


    // Validate if any required parameters are empty
    if (empty($auth_key)) {
        echo json_encode(array('status' => false, 'msg' => 'Empty parameters'));
        exit;
    }

    // Query to check the validity of the auth_key and ensure the user is an administrator
    $sql = "SELECT * FROM auth WHERE auth_key='$auth_key'";
    $key = mysqli_query($conn, $sql);

    // Check if the auth_key is valid
    if (mysqli_num_rows($key) >= 1) {
        // Construct the SQL query to retrieve orders with their status
        $orders_sql = "SELECT 
                orders.*,
                user_addresses.*
                FROM 
                    orders
                INNER JOIN 
                    user_addresses ON orders.address_id = user_addresses.id
                WHERE 
                    orders.id = '$order_id'";

         // Execute the SQL query
         $result = mysqli_query($conn, $orders_sql);
    

        // Check if orders are found
        if (mysqli_num_rows($result) > 0) {
            $orders = array();
            // Fetch each row from the result set
            while ($row = mysqli_fetch_assoc($result)) {

                $items_sql = "SELECT 
                order_items.order_id,
                order_items.status,
                orders_status.status_en,
                order_items.id,
                order_items.product_id,
                products.name_en AS product_name_en,
                products.name_ar AS product_name_ar,
                products.description_en AS product_description_en,
                products.description_ar AS product_description_ar
            FROM 
                order_items
           
            INNER JOIN 
                products ON order_items.product_id = products.id
            INNER JOIN
                orders_status ON order_items.status = orders_status.id
            WHERE 
                order_items.order_id = $order_id";       
                
                
                         $result = mysqli_query($conn, $items_sql);

                         $order_items = array();
                         while ($items_rows = mysqli_fetch_assoc($result)) {


                            $order_items[] = $items_rows;
                         }
                
        
                
                // Add each order to the array
                $row["order_items"] = $order_items;
                $orders = $row;
            }
            // Return the orders as JSON response
            echo json_encode(array('status' => true, 'orders' => $orders));
            mysqli_close($conn);
        } else {
            // Return message if no orders are found
            echo json_encode(array('status' => true, 'msg', 'orders' => []));
            mysqli_close($conn);
        }
    } else {
        // Return authentication error if auth_key is invalid
        echo json_encode(array('status' => false, 'msg' => 'Invalid credentials'));
    }
} else {
    // Return error if any required parameters are missing in the request
    echo json_encode(array('status' => false, 'msg' => 'Required parameters missing'));
}
?>
