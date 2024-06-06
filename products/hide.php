<?php 




header('Content-Type: application/json');


	 include('../config.php');


if( isset($_POST['product_id']) && isset($_POST['user_id'])) {

$auth_key = mysqli_real_escape_string($conn , $_POST['auth_key']);


 $product_id = mysqli_real_escape_string($conn ,$_POST['product_id']);


 
 $added_by = mysqli_real_escape_string($conn ,$_POST['user_id']);

$sql = "SELECT auth_key FROM auth where auth_key='$auth_key'";
 $key = mysqli_query($conn,$sql);
 
 if (mysqli_num_rows($key) >= 1) {

 
  
    
    $edit_sql = "update `products` set active = 0 where id = '$product_id' ";
    


$register = mysqli_query($conn,$edit_sql) or $error = mysqli_error($conn);


	


if ($register) {
    
    


    
echo json_encode(array('status' => true,'msg' => 'success',"products_id"=>$product_id));



mysqli_close($conn);
}

else 
{
 
 echo json_encode(array('status' => false,'msg' => 'Nothing changed',"products_id"=>$error));


mysqli_close($conn);   
    
}




}
else
{
    
     echo json_encode(array('status' => false,'msg' => 'Wrong key',"products_id"=>null));
}

}
else 
{
    
   
    echo json_encode(array('status' => false,'msg' => ' product_id and user_id are required    ',"products_id"=>null));
}


?>