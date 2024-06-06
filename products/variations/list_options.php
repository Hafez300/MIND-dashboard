<?php



header('Content-Type: application/json');



//$data = json_decode(file_get_contents('php://input'),true);



	include('../../config.php');

if(isset($_REQUEST['product_id']) ) {

	$product_id = mysqli_real_escape_string($conn,$_REQUEST['product_id']);

$auth_key = mysqli_real_escape_string($conn,$_REQUEST['auth_key']);




 $sql = "SELECT auth_key FROM auth where auth_key='$auth_key'";
 $key = mysqli_query($conn,$sql);
 
 if (mysqli_num_rows($key) >= 1) {

        

        

     

		

	$query = mysqli_query($conn,"SELECT * from variation_options where main_product_id = '$product_id' ");

	





 if (mysqli_num_rows($query) >= 1) {

	



	while($row = mysqli_fetch_assoc($query)){
	    
	 
	    $temp_array[] = $row;
	    
	}



  





echo json_encode(array('status' => true,'msg' => 'success',"options_list"=>$temp_array));



 }	

 



else

{

	echo json_encode(array('status' => false,'msg' => 'No Data',"options_list"=>[]));

	

}

}

else
{
   echo json_encode(array('status' => false,'msg' => 'Wrong key',"options_list"=>[])); 
    
}

}
else
{
echo json_encode(array('status' => false,'msg' => 'Missing Parameters',"options_list"=>[]));

}


?>