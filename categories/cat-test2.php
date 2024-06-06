<?php



header('Content-Type: application/json');



//$data = json_decode(file_get_contents('php://input'),true);



	include '../config.php';




$conn = $mysqli;

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch categories recursively and return JSON
function fetchCategoriesJSON($parent_id, $conn) {
    $categories = array();
    $sql = "SELECT * FROM categories WHERE parent_id = $parent_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // Recursively call fetchCategoriesJSON for children categories
            $row['children'] = fetchCategoriesJSON($row["id"], $conn);
            $categories[] = $row;
        }
    }
    return $categories;
}

// Fetch top-level categories (categories with no parent)
$top_categories = fetchCategoriesJSON(0, $conn);

// Close MySQL connection
$conn->close();

// Output JSON
echo json_encode($top_categories);
?>
