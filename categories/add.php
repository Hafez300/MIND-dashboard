<?php 




header('Content-Type: application/json');







	 include('../config.php');


if( isset($_POST['user_id'])) {

$auth_key = mysqli_real_escape_string($conn , $_POST['auth_key']);


 $user_id = mysqli_real_escape_string($conn , $_POST['user_id']);

 $cat_name_en = mysqli_real_escape_string($conn ,$_POST['cat_name_en']);
$cat_name_ar = mysqli_real_escape_string($conn ,$_POST['cat_name_ar']);
 
 $parent_id = mysqli_real_escape_string($conn ,$_POST['parent_id']);


$sql = "SELECT auth_key FROM auth where auth_key='$auth_key'";
 $key = mysqli_query($conn,$sql);
 
 if (mysqli_num_rows($key) >= 1) {

 
  	$cat_icon = $_FILES['cat_icon']['name'];
  	
	$filename = explode(".",$cat_icon);
	$filename[0] = time().".".end($filename);



	$cat_icon = $baseurl.$filename[0];
	$target = "icons/".basename($cat_icon);

  	if (move_uploaded_file($_FILES['cat_icon']['tmp_name'], $target)) {

  
  		
  		
  	$icon = $filename[0];
  	
  	}
    
    $rating_sql = "insert into categories (`cat_name_en`,
  `cat_name_ar`,
    `parent_id`,
    `icon`,
    `added_by`
   ) values ('$cat_name_en','$cat_name_ar','$parent_id','$icon','$user_id') ";
    


$register = mysqli_query($conn,$rating_sql) or $error = mysqli_error($conn);


	
$last_id = mysqli_insert_id($conn);
	

if ($register) {
    
    


    
echo json_encode(array('status' => true,'msg' => 'success',"category_id"=>$last_id));



mysqli_close($conn);
}

else 
{
 
 echo json_encode(array('status' => false,'msg' => 'Nothing changed',"category_id"=>$error));


mysqli_close($conn);   
    
}




}
else
{
    
     echo json_encode(array('status' => false,'msg' => 'Wrong key',"rating_id"=>null));
}

}
else 
{
    
    echo json_encode(array('status' => false,'msg' => ' user_id is required',"rating_id"=>null));
}


?>