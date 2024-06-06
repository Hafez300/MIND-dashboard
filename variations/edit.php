<?php 




header('Content-Type: application/json');







	 include('../config.php');


if( isset($_POST['record_id']) && isset($_POST['name_en']) && isset($_POST['name_ar'])) {

$auth_key = mysqli_real_escape_string($conn , $_POST['auth_key']);


$record_id = mysqli_real_escape_string($conn , $_POST['record_id']);

 $name_en = mysqli_real_escape_string($conn , $_POST['name_en']);

 $name_ar = mysqli_real_escape_string($conn ,$_POST['name_ar']);


$sql = "SELECT auth_key FROM auth where auth_key='$auth_key'";
 $key = mysqli_query($conn,$sql);
 
 if (mysqli_num_rows($key) >= 1) {

 
    $edit_sql = "update variations set `name_en` = '$name_en',
  `name_ar` = '$name_ar'
   where id = '$record_id' ";
    


$register = mysqli_query($conn,$edit_sql) or $error = mysqli_error($conn);


	
$last_id = $record_id;
	

if ($register) {
    
    


    
echo json_encode(array('status' => true,'msg' => 'success',"variation_id"=>$last_id));



mysqli_close($conn);
}

else 
{
 
 echo json_encode(array('status' => false,'msg' => 'Nothing changed',"variation_id"=>$error));


mysqli_close($conn);   
    
}




}
else
{
    
     echo json_encode(array('status' => false,'msg' => 'Wrong key',"variation_id"=>null));
}

}
else 
{
    
    echo json_encode(array('status' => false,'msg' => ' name_en and record_id and name_ar are required',"variation_id"=>null));
}


?>