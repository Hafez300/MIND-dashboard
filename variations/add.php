<?php 




header('Content-Type: application/json');







	 include('../config.php');


if( isset($_POST['name_en'])) {

$auth_key = mysqli_real_escape_string($conn , $_POST['auth_key']);


 $name_en = mysqli_real_escape_string($conn , $_POST['name_en']);

 $name_ar = mysqli_real_escape_string($conn ,$_POST['name_ar']);


$sql = "SELECT auth_key FROM auth where auth_key='$auth_key'";
 $key = mysqli_query($conn,$sql);
 
 if (mysqli_num_rows($key) >= 1) {

 
    $add_sql = "insert into variations (`name_en`,
  `name_ar`
   ) values ('$name_en','$name_ar') ";
    


$register = mysqli_query($conn,$add_sql) or $error = mysqli_error($conn);


	
$last_id = mysqli_insert_id($conn);
	

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
    
    echo json_encode(array('status' => false,'msg' => ' name_en is required',"variation_id"=>null));
}


?>