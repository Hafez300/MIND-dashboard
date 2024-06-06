<?php 




header('Content-Type: application/json');



	 include('../config.php');


if( isset($_POST['group_id'])) {

$auth_key = mysqli_real_escape_string($conn , $_POST['auth_key']);

 $group_id = mysqli_real_escape_string($conn , $_POST['group_id']);

 $user_id = mysqli_real_escape_string($conn , $_POST['user_id']);

 $group_name_en = mysqli_real_escape_string($conn ,$_POST['group_name_en']);
$group_name_ar = mysqli_real_escape_string($conn ,$_POST['group_name_ar']);
 
 $parent_id = mysqli_real_escape_string($conn ,$_POST['parent_id']);


$sql = "SELECT auth_key FROM auth where auth_key='$auth_key'";
 $key = mysqli_query($conn,$sql);
 
 if (mysqli_num_rows($key) >= 1) {

 
  if(isset($_FILES['group_icon']['name']))
  {
  	$group_icon = $_FILES['group_icon']['name'];
  	
  	
	$filename = explode(".",$group_icon);
	$filename[0] = time().".".end($filename);



	$group_icon = $baseurl.$filename[0];
	$target = "icons/".basename($group_icon);

  	if (move_uploaded_file($_FILES['group_icon']['tmp_name'], $target)) {

  
  		
  		
  	$icon = $filename[0];
  	
  	}
  	

 
	
	    
	    $icon = " icon = '$icon',";
	}
else 

{
    $icon = " icon = icon, ";
    
    
}
    
    $group_sql = "update groups set `group_name_en` = '$group_name_en',
  `group_name_ar` = '$group_name_ar',
    `parent_id` = '$parent_id',
    $icon
    `added_by` = '$user_id'
    where id = ' $group_id' ";
   
    


$register = mysqli_query($conn,$group_sql) or $error = mysqli_error($conn);


	
$last_id = $group_id;
	

if ($register) {
    
    


    
echo json_encode(array('status' => true,'msg' => 'success',"group_id"=>$last_id));



mysqli_close($conn);
}

else 
{
 
 echo json_encode(array('status' => false,'msg' => 'Nothing changed',"group_id"=>$error));


mysqli_close($conn);   
    
}




}
else
{
    
     echo json_encode(array('status' => false,'msg' => 'Wrong key',"group_id"=>null));
}

}
else 
{
    
    echo json_encode(array('status' => false,'msg' => ' cat_id is required',"group_id"=>null));
}


?>