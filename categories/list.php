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

        

        

     

		

	$query = mysqli_query($conn,"SELECT categories.*,parents.cat_name_en as parent_name_en, parents.cat_name_ar as parent_name_ar FROM categories left join  categories as parents
on categories.parent_id = parents.id where categories.active = 1");

	





 if (mysqli_num_rows($query) >= 1) {

	



	while($row = mysqli_fetch_assoc($query)){
	    
	    	isset($row["icon"])?$row["icon"] =  $baseurl."categories/icons/".$row["icon"]:null;
	    
	    $temp_array[] = $row;
	    
	}



  





echo json_encode(array('status' => true,'msg' => 'success',"categories_list"=>$temp_array));



 }	

 



else

{

	echo json_encode(array('status' => false,'msg' => 'No Data',"categories_list"=>[]));

	

}

}

else
{
   echo json_encode(array('status' => false,'msg' => 'Wrong key',"categories_list"=>[])); 
    
}

}
else
{
echo json_encode(array('status' => false,'msg' => 'Missing Parameters',"categories_list"=>[]));

}


?>