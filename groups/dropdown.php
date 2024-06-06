<?php



header('Content-Type: application/json');



//$data = json_decode(file_get_contents('php://input'),true);



	include('../config.php');

if(isset($_POST['user_id']) ) {

	$user_id = mysqli_real_escape_string($conn,$_POST['user_id']);

$auth_key = mysqli_real_escape_string($conn,$_POST['auth_key']);




 $sql = "SELECT auth_key FROM auth where auth_key='$auth_key'";
 $key = mysqli_query($conn,$sql);
 
 if (mysqli_num_rows($key) >= 1) {

  // $conn = $mysqli;

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch categories recursively and return JSON
function fetchCategoriesJSON($parent_id, $conn) {
    $categories = array();
    $sql = "SELECT * FROM groups WHERE parent_id = $parent_id";
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




echo json_encode(array('status' => true,'msg' => 'success',"groups_list"=>$top_categories));




}

else
{
   echo json_encode(array('status' => false,'msg' => 'Wrong key',"groups_list"=>[])); 
    
}

}
else
{
echo json_encode(array('status' => false,'msg' => 'Missing Parameters',"groups_list"=>[]));

}


?>