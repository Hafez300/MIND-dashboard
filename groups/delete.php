<?php 




header('Content-Type: application/json');



	 include('../config.php');


if( isset($_POST['group_id']) &&  isset($_POST['user_id'])) {

$auth_key = mysqli_real_escape_string($conn , $_POST['auth_key']);

 $group_id = mysqli_real_escape_string($conn , $_POST['group_id']);

 $user_id = mysqli_real_escape_string($conn , $_POST['user_id']);




$sql = "SELECT auth_key FROM auth where auth_key='$auth_key'";
 $key = mysqli_query($conn,$sql);
 
 if (mysqli_num_rows($key) >= 1) {


   
    $group_sql = "delete from groups where id = '$group_id' ";
   
    


$register = mysqli_query($conn,$group_sql) or $error = mysqli_error($conn);



    $product_sql = "update products set group_id = null where group_id = '$group_id' ";
    
    $excute = mysqli_query($conn,$product_sql) or $error = mysqli_error($conn);
    

	

if ($register) {
    
    


    
echo json_encode(array('status' => true,'msg' => 'success',"group_id"=>$group_id));



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