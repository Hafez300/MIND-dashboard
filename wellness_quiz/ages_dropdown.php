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

    $sql = "SELECT * FROM wellness_ages ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          
        $ages[] = $row;
        }
  



echo json_encode(array('status' => true,'msg' => 'success',"ages"=>$ages));




}
else
{
   echo json_encode(array('status' => true,'msg' => 'success',"ages"=>[])); 
    
}
}
else
{
   echo json_encode(array('status' => false,'msg' => 'Wrong key',"ages"=>[])); 
    
}

}
else
{
echo json_encode(array('status' => false,'msg' => 'Missing Parameters',"ages"=>[]));

}


?>